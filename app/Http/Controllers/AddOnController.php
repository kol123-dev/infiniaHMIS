<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AddOnController extends AppBaseController
{
    public function index()
    {
        return view('add-on.index');
    }

    public function extractZip(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:zip'
        ]);

        $file = $request->file('file');
        $filePathInfo = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extractionPath = base_path('Modules/');
        $moduleFolder = $extractionPath . $filePathInfo;

        if (is_dir($moduleFolder)) {
            return $this->sendError(__('messages.addon.module_folder_already_exists'));
        }

        $isExistFiles = [
            $filePathInfo . '/' . 'composer.json',
            $filePathInfo . '/' . 'Providers/RouteServiceProvider.php'
        ];
        $zip = new ZipArchive;

        if ($zip->open($file) === TRUE) {
            $fileNames = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileNames[] = $zip->getNameIndex($i);
            }

            $checkFiles = [];
            foreach ($isExistFiles as $isExistFile) {
                if (!in_array($isExistFile, $fileNames)) {
                    $checkFiles[] = $isExistFile;
                }
            }

            $zip->close();

            if (!empty($checkFiles)) {
                return $this->sendError(__('messages.addon.zip_required_file'));
            }

            if ($zip->open($file) === TRUE) {
                $zip->extractTo($extractionPath);
                $zip->close();

                $addOn = AddOn::updateOrCreate([
                    'name' => $filePathInfo,
                ]);

                $content = file_get_contents(base_path("modules_statuses.json"));
                $content = json_decode($content, true);
                $content[$filePathInfo] = true;
                file_put_contents(base_path("modules_statuses.json"), json_encode($content));
            } else {
                return $this->sendError(__('messages.addon.failed_to_extraction'));
            }

            \Artisan::call('module:migrate', ['--force' => true]);


            return $this->sendSuccess(__('messages.addon.addon_uploaded_successfully'));
        } else {
            return $this->sendError(__('messages.addon.failed_to_open'));
        }
    }

    public function update($id)
    {
        $addOnModule = AddOn::find($id);

        if (!$addOnModule) {
            return $this->sendError(__('messages.addon.module_not_found'));
        }

        $addOnModule->status = !$addOnModule->status;

        $addOnModule->save();

        return $this->sendSuccess(__('messages.addon.module_status_updated'));
    }

    public function delete($id)
    {
        $addOnModule = AddOn::find($id);

        if (!$addOnModule) {
            return $this->sendError(__('messages.addon.module_not_found'));
        }
        $moduleName = $addOnModule->name;
        $modulePath = base_path('Modules/' . $moduleName);

        if (is_dir($modulePath)) {
            $this->deleteDirectory($modulePath);
        }

        $modulesStatusesPath = base_path('modules_statuses.json');
        if (file_exists($modulesStatusesPath)) {
            $content = json_decode(file_get_contents($modulesStatusesPath), true);

            if (isset($content[$moduleName])) {
                unset($content[$moduleName]);
                file_put_contents($modulesStatusesPath, json_encode($content));
            }
        }

        $addOnModule->delete();
        return $this->sendSuccess(__('messages.admin') . ' ' . __('messages.common.deleted_successfully'));
    }

    private function deleteDirectory($directory)
    {
        foreach (scandir($directory) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $directory . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                $this->deleteDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }

        rmdir($directory);
    }
}
