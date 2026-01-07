<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Ticket;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => ['required', 'exists:events,id'],
            'tickets' => ['required', 'array'],
            'tickets.*.ticket_id' => ['required', 'exists:tickets,id'],
            'tickets.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);

        throw (new ValidationException($validator, $response));
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $eventId = $this->input('event_id');

            foreach ($this->input('tickets', []) as $index => $item) {
                $ticket = Ticket::find($item['ticket_id']);

                if (!$ticket) {
                    continue;
                }

                if ($ticket->event_id !== (int) $eventId) {
                    $validator->errors()->add(
                        "tickets.$index.ticket_id",
                        "Ticket does not belong to the selected event."
                    );
                }

                if ($ticket->available_quantity < $item['quantity']) {
                    $validator->errors()->add(
                        "tickets.$index.quantity",
                        "Not enough tickets available."
                    );
                }
            }
        });

    }

}
