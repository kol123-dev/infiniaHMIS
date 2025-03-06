<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\InvoiceItem;

/**
 * Class InvoiceItemRepository
 *
 * @version February 24, 2020, 5:57 am UTC
 */
class InvoiceItemRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'account_id',
        'description',
        'quantity',
        'price',
        'total',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return InvoiceItem::class;
    }

    public function updateInvoiceItem($invoiceItemInput, $invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        $invoiceItemIds = [];

        foreach ($invoiceItemInput as $key => $data) {
            if (isset($data['id']) && ! empty($data['id'])) {
                $invoiceItemIds[] = $data['id'];
                $this->update($data, $data['id']);
            } else {
                $invoiceItem = new InvoiceItem($data);
                $invoiceItem = $invoice->invoiceItems()->save($invoiceItem);
                $invoiceItemIds[] = $invoiceItem->id;
            }
        }

        if (! (isset($invoiceItemIds) && count($invoiceItemIds))) {
            return;
        }

        InvoiceItem::whereNotIn('id', $invoiceItemIds)->whereInvoiceId($invoice->id)->delete();
    }
}
