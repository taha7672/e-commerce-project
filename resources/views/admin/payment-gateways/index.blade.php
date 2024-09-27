@extends('layouts.admin')

@section('breadcrumb',  __('pages.payment_gateways'))

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('admin-assets/plugins/src/table/datatable/datatables.css')}}">
@endpush
@section('content')
    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row mb-4">
                        <div class="col-8">
                            <h4>{{__('pages.payment_integration')}}</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a class="btn btn-success" href="{{route('admin.payment-gateways.create')}}">{{__('pages.add_new_payment_integration')}}</a>
                        </div>
                    </div>
                                
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table id="gateways" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('tables.id') }}</th>
                                <th>{{ __('tables.name') }}</th>
                                <th>{{ __('tables.status') }}</th>
                                <th>{{ __('tables.default') }}</th>
                                <th>{{ __('tables.actions') }}</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentGateways as $gateway)
                                <tr>
                                    <td>{{ $gateway->id }}</td>
                                    <td>{{ $gateway->name }}</td>
                                    <td>{{ $gateway->status ? __('pages.active') : __('pages.inactive') }}</td>
                                    <td>{{ $gateway->is_default ?  __('pages.yes') : __('pages.no') }}</td>
                                    <td>
                                        <a href="{{ route('admin.payment-gateways.edit', $gateway->id) }}" class="btn btn-sm btn-warning">{{__('pages.edit')}}</a>

                                        <form action="{{ route('admin.payment-gateways.destroy', $gateway->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{__('pages.delete')}}</button>
                                        </form>

                                        @if (!$gateway->is_default)
                                            <form action="{{ route('admin.payment-gateways.setDefault', $gateway->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">{{__('pages.set_as_default')}}</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts') 
    <script src="{{asset('admin-assets/plugins/src/table/datatable/datatables.js')}}"></script>
    <script>
        $('#gateways').DataTable({ 
            "lengthMenu": [5, 10, 20, 50],
            "pageLength": 20
        });
    </script>
@endpush 