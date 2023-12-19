@extends('layouts.admin')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script type="text/javascript">
    function getData() {
        return {
            // Show Modal
            showProductPicker: false,

            // Input
            selectedCategoryId: null,
            selectedSubCategoryId: null,
            searchQuery: null,

            // Data
            categories: [],
            subCategories: [],
            products: [],

            // Functions
            fetchCategories() {
                fetch(`/api/categories`)
                    .then((res) => res.json())
                    .then((data) => {
                        this.categories = data.categories;
                    });
            },

            fetchSubCategories(){
                fetch(`/api/categories/${this.selectedCategoryId}`)
                    .then((res) => res.json())
                    .then((data) => {
                        this.selectedSubCategoryId = null;
                        this.subCategories = data?.category?.children ?? [];
                    });
            },

            fetchProducts(){
                fetch(`/api/products?category_id=${this.selectedSubCategoryId ?? this.selectedCategoryId}`)
                    .then((res) => res.json())
                    .then((data) => {
                        this.products = data?.products ?? [];
                    });
            }
        };
    }
</script>

<div x-data="getData()">
    {{-- EDIT ORDER FORM --}}
    <form class="p-8 ps-0" method="post" action="/admin/orders/{{$order->id}}">
        @csrf
        @method('put')
        <div class="md:mb-5">
            {{-- Order --}}
            <div class="flex flex-row text-gray-700 mx-2 gap-12 bg-gray-100 border items-start rounded-lg p-5">
                    <div class="text-start">
                        <div class="font-semibold mb-2">Customer</div>
                        @if($order->user)
                            {{$order->user->first_name}} {{$order->user->last_name}}<br>
                            {{$order->user->email}}
                        @else
                            <div class="text-red-500 mb-2">No User</div>
                        @endif
                    </div>
                    <div class="text-start">
                        <div class="font-semibold mb-2">Status</div>
                        <select class="p-3 bg-blue-50 border-blue-300 w-40 border rounded-lg" name="status">
                            <option value="unverified" @if($order->status == 'unverified') selected @endif>Unverified</option>
                            <option value="pending" @if($order->status == 'pending') selected @endif>Pending</option>
                            <option value="processing" @if($order->status == 'processing') selected @endif>Processing</option>
                            <option value="shipped" @if($order->status == 'shipped') selected @endif>Shipped</option>
                            <option value="delivered" @if($order->status == 'delivered') selected @endif>Delivered</option>
                            <option value="returned" @if($order->status == 'returned') selected @endif>Returned</option>
                            <option value="canceled" @if($order->status == 'canceled') selected @endif>Canceled</option>
                            <option value="rejected" @if($order->status == 'rejected') selected @endif>Rejected</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                    <div class="text-start">
                        <div class="font-semibold mb-2">Payment Status</div>
                        <select class="p-3 bg-blue-50 border-blue-300 w-40 border rounded-lg" name="payment_status">
                            <option value="unpaid" @if($order->payment_status == 'unpaid') selected @endif>Unpaid</option>
                            <option value="paid" @if($order->payment_status == 'paid') selected @endif>Paid</option>
                            <option value="partial" @if($order->payment_status == 'partial') selected @endif>Partial</option>
                            <option value="refunded" @if($order->payment_status == 'refunded') selected @endif>Refunded</option>
                        </select>
                        <x-input-error :messages="$errors->get('payment_status')" class="mt-2" />
                    </div>
                    <div class="text-start">
                        <div class="font-semibold mb-2">Order Type {{$order->type }}</div>
                        <select class="p-3 bg-blue-50 border-blue-300 w-40 border rounded-lg" name="order_type">
                            <option value="wholesale" @if($order->order_type == 'wholesale') selected @endif>Wholesale Order</option>
                            <option value="retail" @if($order->order_type == 'retail') selected @endif>Retail Order</option>
                        </select>
                        <x-input-error :messages="$errors->get('order_type')" class="mt-2" />
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
                    @php
                        $content_editable = ($order->status == 'unverified' || $order->status == 'pending') && $order->order_type != 'retail';
                    @endphp
                    <!-- Order items -->
                    @foreach($order->items as $item)
                    <x-order-item-row :item="$item" :admin="$content_editable" />
                    @endforeach
                    @endif
                </div>
            </div>

            {{-- Products --}}
            <div class="flex flex-col mx-2  md:mt-5 border rounded-lg">
                <div class="text-blue-900">
                    <div class="flex flex-row justify-between items-center p-5 bg-gray-100">
                        <button class="bg-blue-200 w-32 rounded h-10" x-on:click="showProductPicker = true" type="button">Add Item</button>
                        <p class="w-full text-start font-semibold"></p>
                        <div class="w-1/6 text-start font-semibold">
                            Products: <br>
                            <p class="text-xl">{{$order->items->count()}}</p>
                        </div>
                        <div class="w-1/6 text-start font-semibold">
                            Quantity: <br>
                            <p class="text-xl">{{$order->items->sum('quantity')}}</p>
                        </div>
                        @if(!Auth::user()->is_wholesale())
                        <div class="w-1/6 text-start font-semibold">
                            Amount: <br>
                            <p class="text-xl">${{$order->total()}}</p>
                        </div>
                        @endif
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
            <a href="/admin/orders/{{$order->id}}/print"><button type="button" class="border-2 border-gray-700 bg-gray-800 text-white py-2 px-5 rounded-lg w-40 mx-2">Print Invoice</button></a>
            <button type="submit" class="border-2 border-blue-700 bg-blue-800 text-white py-2 px-5 rounded-lg w-40 mx-2">Save</button>
        </div>
    </form>

    {{-- FIND PRODUCT --}}
    <div class="relative z-10" role="dialog" x-show="showProductPicker" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 backdrop-blur-sm backdrop-contrast-125 bg-opacity-75 transition-opacity" x-on:click="showProductPicker = false"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-auto rounded-lg bg-white text-left shadow-xl transition-all sm:my-8" x-on:click.outside="showProductPicker = false">
                    <div class="flex flex-col p-5 gap-4 text-gray-800 max-w-screen-lg">
                        <h1 class="text-lg font-bold">Find Products</h1>
                        <div class="w-full flex justify-between">
                            <div class="flex items-center gap-2">

                                <div class="flex flex-col gap-1" x-init="fetchCategories()">
                                    <label for="category">Category</label>
                                    <select class="p-2 rounded-lg h-10 me-3 max-w-[200px]" name="category" x-model="selectedCategoryId" x-on:change="fetchSubCategories()">
                                        <option value="">SELECT CATEGORY</option>
                                        <template x-for="category in categories">
                                            <option x-text="category.name" x-bind:value="category.id"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <label for="sub_category">Sub Category</label>
                                    <select class="p-2 rounded-lg h-10 me-3 max-w-[200px] disabled:bg-gray-400" name="sub_category" x-model="selectedSubCategoryId" x-bind:disabled="subCategories.length === 0">
                                        <option value="">SELECT SUB CATEGORY</option>
                                        <template x-for="subCategory in subCategories">
                                            <option x-text="subCategory.name" x-bind:value="subCategory.id"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <label for="search">Search</label>
                                    <input class="p-2 rounded-lg h-10 border border-gray-500 me-3" x-model="searchQuery" id="search" />
                                </div>
                                <button class="bg-gray-300 p-2 h-10 w-10 rounded-lg mt-auto" x-on:click="fetchProducts()">Go</button>
                            </div>
                        </div>
                        <hr>
                        <div class="flex flex-col gap-2 max-h-64 overflow-auto">
                            <template x-for="product in products">                                
                                <div class="w-full p-2 gap-4 bg-gray-200 rounded-lg flex items-center">
                                    <img class="w-1/12 rounded-lg bg-white aspect-square object-cover" src=""
                                        alt="Product Image">
                                    <h3 class="w-3/12 ">Product Title</h3>
                                    <h3 class="w-3/12 ">Category</h3>
                                    <h3 class="w-3/12 ">Variant - Price</h3>
                                    <button class="h-12 w-12 rounded-lg bg-gray-800 text-white">Add</button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection