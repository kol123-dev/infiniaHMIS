<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseMedicineExport;
use App\Http\Requests\CreatePurchaseMedicineRequest;
use App\Models\Medicine;
use App\Models\PurchasedMedicine;
use App\Models\PurchaseMedicine;
use App\Repositories\AppointmentTransactionRepository;
use App\Repositories\IpdPaymentRepository;
use App\Repositories\MedicineBillRepository;
use App\Repositories\MedicineRepository;
use App\Repositories\PurchaseMedicineRepository;
use DB;
use Exception;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Unicodeveloper\Paystack\Facades\Paystack;

class PurchaseMedicineController extends AppBaseController
{
    /** @var PurchaseMedicineRepository */
    /** @var MedicineRepository */
    /** @var MedicineBillRepository */
    /** @var IpdPaymentRepository */
    /** @var AppointmentTransactionRepository */
    private $ipdPaymentRepository;
    private $purchaseMedicineRepository;
    private $appointmentTransactionRepository;
    private $medicineRepository;
    private $medicineBillRepository;

    public function __construct(PurchaseMedicineRepository $purchaseMedicineRepo, MedicineRepository $medicineRepository, MedicineBillRepository $medicineBillRepository, IpdPaymentRepository $ipdPaymentRepo, AppointmentTransactionRepository $appointmentTransactionRepository,)
    {
        $this->purchaseMedicineRepository = $purchaseMedicineRepo;
        $this->medicineRepository = $medicineRepository;
        $this->medicineBillRepository = $medicineBillRepository;
        $this->ipdPaymentRepository = $ipdPaymentRepo;
        $this->appointmentTransactionRepository = $appointmentTransactionRepository;

        config([
            'paystack.publicKey' => getPaymentCredentials('paystack_public_key'),
            'paystack.secretKey' => getPaymentCredentials('paystack_secret_key'),
            'paystack.paymentUrl' => 'https://api.paystack.co',
        ]);
    }

    public function index()
    {

        return view('purchase-medicines.index');
    }

    public function create()
    {

        $data = $this->medicineRepository->getSyncList();
        $medicines = $this->purchaseMedicineRepository->getMedicine();
        $medicineList = $this->purchaseMedicineRepository->getMedicineList();
        $categories = $this->purchaseMedicineRepository->getCategory();
        $categoriesList = $this->purchaseMedicineRepository->getCategoryList();

        return view('purchase-medicines.create', compact('medicines', 'medicineList', 'categories', 'categoriesList'))->with($data);
    }

    public function setFlutterWaveCredential()
    {
        $flutterwavePublicKey = getPaymentCredentials('flutterwave_public_key');
        $flutterwaveSecretKey = getPaymentCredentials('flutterwave_secret_key');

        if (!$flutterwavePublicKey && !$flutterwaveSecretKey) {
            return $this->sendError(__('messages.flutterwave.set_flutterwave_credential'));
        }

        config([
            'flutterwave.publicKey' => $flutterwavePublicKey,
            'flutterwave.secretKey' => $flutterwaveSecretKey,
        ]);
    }

    public function store(CreatePurchaseMedicineRequest $request)
    {
        $input = $request->all();

        try {
            DB::beginTransaction();

            if ($input['payment_type'] == PurchaseMedicine::PURCHASE_MEDICINE_STRIPE) {

                $this->purchaseMedicineRepository->store($input);
                $result = $this->purchaseMedicineRepository->stripeSession($input);
                DB::commit();

                return $this->sendResponse(['payment_type' => $input['payment_type'], $result], 'stripe session created successfully.');
            } elseif ($input['payment_type'] == PurchaseMedicine::PURCHASE_MEDICINE_RAZORPAY) {
                $this->purchaseMedicineRepository->store($input);
                DB::commit();

                return $this->sendResponse(['payment_type' => $input['payment_type'], 'purchase_no' => $input['purchase_no']], 'razorpay session created successfully.');
            } elseif ($input['payment_type'] == PurchaseMedicine::PURCHASE_MEDICINE_PAYSTACK) {

                return $this->sendResponse(['payStackData' => $input], 'paystack session created successfully.');
            } elseif ($input['payment_type'] == PurchaseMedicine::PURCHASE_MEDICINE_PHONEPE) {

                if (strtoupper(getCurrentCurrency()) != 'INR') {
                    return $this->sendError(__('messages.payment.phonepe_support_inr'));
                }

                $result = $this->purchaseMedicineRepository->phonePePayment($input);

                return $this->sendResponse(['url' => $result, 'payment_type' => $input['payment_type']], 'phonepe session created successfully.');
            } elseif ($input['payment_type'] == PurchaseMedicine::PURCHASE_MEDICINE_FLUTTERWAVE) {

                if (!in_array(strtoupper(getCurrentCurrency()), getFlutterWaveSupportedCurrencies())) {
                    return $this->sendError(__('messages.payment.flutterwave_not_support'));
                }

                $this->setFlutterWaveCredential();

                session(['fluttterWaveInput' => $input]);

                $result = $this->purchaseMedicineRepository->flutterWavePayment($input);

                return $this->sendResponse(['url' => $result, 'payment_type' => $input['payment_type']], 'FlutterWave created successfully');
            } else {
                $this->purchaseMedicineRepository->store($input);
                DB::commit();

                return $this->sendSuccess(__('messages.purchase_medicine.medicine_purchased_successfully'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function show(PurchaseMedicine $medicinePurchase)
    {
        $medicinePurchase->load(['purchasedMedcines.medicines']);

        return view('purchase-medicines.show', compact('medicinePurchase'));
    }

    public function getMedicine(Medicine $medicine)
    {
        $medicineExpiryDate = PurchasedMedicine::where('medicine_id', $medicine->id)->latest()->first();
        $medicine['expiry_date'] = $medicineExpiryDate->expiry_date ?? null;

        return $this->sendResponse($medicine, 'retrieved');
    }

    public function purchaseMedicineExport()
    {
        $purchaseMedicines = PurchaseMedicine::with('purchasedMedcines')->get();
        if (!$purchaseMedicines) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('medicine-purchase.index'));
        }
        $response = Excel::download(new PurchaseMedicineExport, 'purchase-medicine-' . time() . '.xlsx');

        ob_end_clean();

        return $response;
    }

    public function usedMedicine()
    {

        return view('used-medicine.index');
    }

    public function destroy(PurchaseMedicine $medicinePurchase)
    {
        $medicinePurchase->delete();

        return $this->sendSuccess(__('messages.flash.medicine_deleted'));
    }

    public function stripeSuccess(Request $request)
    {

        $this->purchaseMedicineRepository->purchaseMedicinestripeSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('medicine-purchase.index'));
    }

    public function stripeFail(Request $request)
    {

        $input = $request->all();

        $purchaseMedicine = PurchaseMedicine::where('purchase_no', $input['input']['purchase_no'])->first();
        $purchaseMedicine->delete();

        foreach ($input['input']['medicine'] as $key => $value) {
            $medicine = Medicine::find($input['input']['medicine'][$key]);
            $medicineQtyArray = [
                'quantity' => $medicine->quantity - $input['input']['quantity'][$key],
                'available_quantity' => $medicine->available_quantity - $input['input']['quantity'][$key],
            ];
            $medicine->update($medicineQtyArray);
        }

        Flash::error(__('messages.payment.payment_failed'));

        return redirect(route('medicine-purchase.index'));
    }

    public function razorPayInit(Request $request)
    {
        $result = $this->purchaseMedicineRepository->razorPayPayment($request->all());

        return $this->sendResponse($result, 'RazorPay order created successfully.');
    }

    public function razorPaySuccess(Request $request)
    {
        $this->purchaseMedicineRepository->razorPaySuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('medicine-purchase.index'));
    }

    public function razorPayFailed(Request $request)
    {

        $input = $request->all();

        $purchaseMedicine = PurchaseMedicine::where('purchase_no', $input['purchase_no'])->first();
        $purchaseMedicine->delete();

        foreach ($input['medicine'] as $key => $value) {
            $medicine = Medicine::find($input['medicine'][$key]);
            $medicineQtyArray = [
                'quantity' => $medicine->quantity - $input['quantity'][$key],
                'available_quantity' => $medicine->available_quantity - $input['quantity'][$key],
            ];
            $medicine->update($medicineQtyArray);
        }

        return $this->sendSuccess(__('messages.payment.payment_failed'));
    }

    public function paystackPayment(Request $request)
    {
        if (!in_array(strtoupper(getCurrentCurrency()), getPayStackSupportedCurrencies())) {
            Flash::error(__('messages.payment.paystack_support_zar'));

            return redirect(route('medicine-purchase.index'));
        }

        $data = $request->data;
        $amount = $request->net_amount;
        $purchaseNo = $request->purchase_no;

        try {
            $request->merge([
                'email' => getLoggedInUser()->email,
                'orderID' => $purchaseNo,
                'amount' => $amount * 100,
                'quantity' => 1,
                'currency' => 'ZAR',
                'reference' => Paystack::genTranxRef(),
                'metadata' => json_encode(['data' => $data]),
            ]);
            $authorizationUrl = Paystack::getAuthorizationUrl();

            return $authorizationUrl->redirectNow();
        } catch (\Exception $e) {
            Flash::error(__('messages.payment.payment_failed'));

            return redirect(route('medicine-purchase.index'));
        }
    }

    public function paystackPaymentSuccess(Request $request)
    {
        $paymentDetails = Paystack::getPaymentData();

        if (isset($paymentDetails['data']['metadata']['data']['appointment_charge'])) {

            $this->appointmentTransactionRepository->payStackPaymentSuccess($paymentDetails);

            $isWebAppointment = isset($paymentDetails['data']['metadata']['data']['web_appointment']);

            if (isset($isWebAppointment) && $isWebAppointment == true) {

                Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

                return redirect()->route('appointment');
            }

            Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

            return redirect()->route('appointments.index');
        }

        if (isset($paymentDetails['data']['metadata']['data']['purchase_no'])) {

            $this->purchaseMedicineRepository->paystackPaymentSuccess($paymentDetails);

            Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

            return redirect(route('medicine-purchase.index'));
        }

        if (isset($paymentDetails['data']['metadata']['data']['patient_id'])) {
            $this->medicineBillRepository->paystackPaymentSuccess($paymentDetails);

            Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

            return redirect(route('medicine-bills.index'));
        }

        if (isset($paymentDetails['data']['metadata']['ipd_patient_id'])) {
            $this->ipdPaymentRepository->ipdPaystackPaymentSuccess($paymentDetails);

            Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

            if (getLoggedinPatient()) {
                return redirect(route('patient.ipd'));
            }

            return redirect(route('ipd.patient.index'));
        }
    }

    public function phonePePaymentSuccess(Request $request)
    {
        $this->purchaseMedicineRepository->phonePePaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect()->route('medicine-purchase.index');
    }

    public function flutterwavePaymentSuccess(Request $request)
    {
        $this->setFlutterWaveCredential();

        if ($request['status'] == 'cancelled') {
            Flash::error(__('messages.payment.payment_failed'));

            return redirect()->route('medicine-purchase.index');
        }

        $this->purchaseMedicineRepository->flutterwavePaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect()->route('medicine-purchase.index');
    }
}
