<?php

namespace App\Repositories;

use App\Models\BloodDonation;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BloodDonationRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'blood_donor_id',
        'donation_date',
        'bags',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return BloodDonation::class;
    }

    public function createBloodDonation($input)
    {
        try {
            $bloodDonation = $this->create($input);

            $bloodDonation = BloodDonation::with('bloodDonor.bloodBank')->find($bloodDonation->id);
            $bloodBank = $bloodDonation->bloodDonor->bloodBank;
            $remainedBags = $bloodBank->remained_bags + $input['bags'];
            $bloodBank->update(['remained_bags' => $remainedBags]);

            $bloodDonation->bloodDonor->update(['last_donate_date' => $bloodDonation->created_at]);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateBloodDonation($input, $bloodDonation)
    {
        try {
            $bloodDonation = BloodDonation::with('bloodDonor.bloodBank')->find($bloodDonation->id);
            $currentBags = $bloodDonation->bags;

            $bloodDonation = $this->update($input, $bloodDonation->id);

            $bloodBank = $bloodDonation->bloodDonor->bloodBank;
            $remainedBags = ($bloodBank->remained_bags - $currentBags) + $input['bags'];

            $bloodBank->update(['remained_bags' => $remainedBags]);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
