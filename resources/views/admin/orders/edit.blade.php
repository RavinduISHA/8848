@extends('layouts.admin')
@section('content')


<form class="p-8 ps-0" method="post" action="/admin/orders/{{$order->id}}">
    @csrf
    @method('put')
    <div class="md:mb-5">
        {{-- Order --}}
        <div class="flex flex-row text-gray-700 mx-2 gap-12 bg-gray-100 border items-start rounded-lg p-5">
                <div class="text-start">
                    <div class="font-semibold mb-2">Customer</div>
                    {{$order->user->first_name}} {{$order->user->last_name}}<br>
                    {{$order->user->email}}
                </div>
                <div class="text-start">
                    <div class="font-semibold mb-2">Status</div>
                    <select class="p-3 bg-blue-50 border-blue-300 w-40 border rounded-lg" name="status">
                        <option value="pending" @if($order->status == 'pending') selected @endif>Pending</option>
                        <option value="shipped" @if($order->status == 'shipped') selected @endif>Shipped</option>
                        <option value="delivered" @if($order->status == 'delivered') selected @endif>Delivered</option>
                        <option value="canceled" @if($order->status == 'canceled') selected @endif>Canceled</option>
                    </select>
                </div>
                <div class="text-start">
                    <div class="font-semibold mb-2">Payment Status</div>
                    <select class="p-3 bg-blue-50 border-blue-300 w-40 border rounded-lg" name="payment_status">
                        <option value="unpaid" @if($order->payment_status == 'unpaid') selected @endif>Unpaid</option>
                        <option value="paid" @if($order->payment_status == 'paid') selected @endif>Paid</option>
                        <option value="partial" @if($order->payment_status == 'partial') selected @endif>Partial</option>
                    </select>
                </div>
                <div class="text-start ms-auto">
                    <b class="font-semibold">Date:</b> {{$order->created_at}}
                </div>
            </div>

            {{-- Customer Detais End --}}
            <div class="flex flex-col mx-2 mt-4 border rounded-lg">
                <div class="text-blue-900">
                    <div class="flex flex-row p-5 bg-gray-100">
                        <p class="w-1/6 text-start font-semibold">Product</p>
                        <p class="w-2/6 text-start font-semibold"></p>
                        <p class="w-1/6 text-start font-semibold">Price</p>
                        <p class="w-1/6 text-start font-semibold">Custom Price</p>
                        <p class="w-1/6 text-start font-semibold">Quantity</p>
                        <p class="w-1/6 text-start font-semibold">Subtotal</p>
                    </div>
                    @if($order->items->count() < 1) <div class="flex flex-row items-center p-5">
                        <div class="w-full text-center py-12">This order has no products</div>
                </div>
                @else
                <!-- Order items -->
                @foreach($order->items as $item)
                <x-order-item-row :item="$item" />
                @endforeach
                @endif
            </div>
        </div>

        {{-- Products --}}
        <div class="flex flex-col mx-2  md:mt-5 border rounded-lg">
            <div class="text-blue-900">
                <div class="flex flex-row justify-end p-5 bg-gray-100">
                    <p class="w-5/6 text-start font-semibold"></p>
                    <div class="w-1/6 text-start font-semibold">
                        Items: <br>
                        <p class="text-xl">{{$order->items->sum('quantity')}}</p>
                    </div>
                    <div class="w-1/6 text-start font-semibold">
                        Total: <br>
                        <p class="text-xl">${{$order->total()}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--
    <div class="border-2 py-5 px-5 md:mt-5 mx-2">
        <p class="text-center font-bold">Payment Details</p>

        <div class="flex flex-row justify-between md:mt-5">
            <p> Payment ID</p>
            <p> Payment Date</p>
            <p> Payment Method</p>
            <p> Card Number</p>
            <p> Amount</p>
            <p> Balance</p>
            <p> Status</p>
        </div>
    </div>
    --}}

    {{-- ADDRESSES --}}
    <div class="flex flex-row w-full text-blue-900">
        <div class="w-6/12 border rounded-lg bg-gray-100 mx-2">
            <p class="font-bold px-5 pt-5">Billing Address</p>

            <div class="p-5 flex gap-4">
                <div>
                    <p><b>First Name:</b> {{$order->billing_first_name}}</p>
                    <p><b>Last Name:</b> {{$order->billing_last_name}}</p>
                    <p><b>Phone:</b> {{$order->billing_phone}}</p>
                    <p><b>Company:</b> {{$order->billing_company}}</p>
                    <p><b>Address:</b> {{$order->billing_address_line_1}}</p>
                    <p>{{$order->billing_address_line_2}}</p>
                </div>
                <div>
                    <p><b>City:</b> {{$order->billing_city}}</p>
                    <p><b>ZIP:</b> {{$order->billing_zip}}</p>
                    <p><b>State:</b> {{$order->billing_state}}</p>
                </div>
            </div>
        </div>

        <div class="w-6/12 border rounded-lg bg-gray-100 mx-2">
            <p class="font-bold px-5 pt-5">Shipping Address</p>
            <div class="p-5 flex gap-4">
                <div>
                    <p><b>First Name:</b> {{$order->shipping_first_name}}</p>
                    <p><b>Last Name:</b> {{$order->shipping_last_name}}</p>
                    <p><b>Phone:</b> {{$order->shipping_phone}}</p>
                    <p><b>Company:</b> {{$order->shipping_company}}</p>
                    <p><b>Address:</b> {{$order->shipping_address_line_1}}</p>
                    <p>{{$order->shipping_address_line_2}}</p>
                </div>
                <div>
                    <p><b>City:</b> {{$order->shipping_city}}</p>
                    <p><b>ZIP:</b> {{$order->shipping_zip}}</p>
                    <p><b>State:</b> {{$order->shipping_state}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end mx-auto md:mt-5">
        <button type="submit" class="border-2 border-blue-700 bg-blue-800 text-white py-2 px-5 rounded-lg w-40 mx-2">Save</button>
    </div>
</form>
@endsection