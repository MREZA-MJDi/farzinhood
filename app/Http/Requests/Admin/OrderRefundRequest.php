<?php

namespace App\Http\Requests\Admin;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class OrderRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true
            && $this->user()?->is_active === true;
    }

    public function rules(): array
    {
        $order = $this->route('order');
        $remaining = 0;

        if ($order instanceof Order) {
            $refundedAmount = (int) $order->payments()
                ->where('status', 'refunded')
                ->sum('amount');

            $remaining = max(0, (int) $order->total - $refundedAmount);
        }

        return [
            'amount' => [
                'required',
                'integer',
                'min:1',
                'max:'.$remaining,
            ],
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'amount' => $this->integer('amount'),
        ]);
    }
}
