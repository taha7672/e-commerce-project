@extends('layouts.admin')

@section('breadcrumb', __('pages.notifications'))

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/src/table/datatable/datatables.css') }}">
    <style>
        .box-margin {
            margin: 0px 170px;
        }

        .notification_div {
            width: 100%;
            display: flex;
        }

        .notification_img_div {
            width: 80px;
        }

        .profile_img {
            border-radius: 50%;
        }

        .content_div {
            width: 100%;
        }

        .message {
            font-size: 16px;
            color: #191e3a;
        }

        .unread_notification,
        .all_notification {
            font-size: 16px;
        }
    </style>
@endpush
@section('content')
    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-8 box-margin">
                    <div class="row mb-4">
                        <div class="col-8">
                            <h4>{{__('pages.notifications')}}</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a class="btn btn-success" href="{{ route('admin.notification.read') }}">{{__('pages.mark_all_as_read')}}</a>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-1">
                            <a class="all_notification text-primary">{{__('pages.all')}}</a>
                        </div>
                        <div class="col-1">
                            <a class="unread_notification">{{__('pages.unread')}}</a>
                        </div>
                    </div>
                    <div class="all_notification_div">
                        @if (count($notifications) > 0)
                            @foreach ($notifications as $notification)
                                <div class="notification_div">
                                    <div class="notification_img_div">

                                        <img src="{{ asset('admin-assets/img/dummy.png') }}"
                                            class="profile_img img-fluid me-2" alt="avatar">

                                    </div>
                                    <div class="content_div">
                                        <div class="p-2">
                                            <p class="message">{{ ucfirst($notification->message) }} @if ($notification->order != null)
                                                    <a href="{{ route('admin.orders.show', $notification->order->id) }}"
                                                        class="text-primary"
                                                        target="_blank">{{ '#' }}{{ $notification->order->id }}
                                                    </a>
                                                @endif
                                            </p>
                                            <b class="text-primary ago text-capitalize">{{ $notification->created_at }}</b>
                                        </div>

                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        @else
                            <hr>
                            <div>
                                <p class="text-center">{{__('pages.no_notifications_found')}}</p>
                            </div>
                            <hr>
                        @endif
                        {{ $notifications->links() }}
                    </div>
                    <div class="unread_notification_div d-none">
                        @if (count($unreadNotifications) > 0)
                            @foreach ($unreadNotifications as $unreadNotification)
                                <div class="notification_div">
                                    <div class="notification_img_div">

                                        <img src="{{ asset('admin-assets/img/dummy.png') }}"
                                            class="profile_img img-fluid me-2" alt="avatar">

                                    </div>
                                    <div class="content_div">
                                        <div class="p-2">
                                            <p class="message">{{ $unreadNotification->message }} @if ($unreadNotification->order != null)
                                                    <a href="{{ route('admin.orders.show', $unreadNotification->order->id) }}"
                                                        class="text-primary"
                                                        target="_blank">{{ '#' }}{{ $unreadNotification->order->id }}
                                                    </a>
                                                @endif
                                            </p>
                                            <b
                                                class="text-primary ago_2 text-capitalize">{{ $unreadNotification->created_at }}</b>
                                        </div>

                                    </div>


                                </div>
                                <hr>
                            @endforeach
                        @else
                            <hr>
                            <div>
                                <p class="text-center">{{__('pages.no_unread_notifications')}}</p>
                            </div>
                            <hr>


                        @endif
                        {{ $unreadNotifications->links() }}

                    </div>
                </div>
            </div>
        </div>



    </div>


@endsection

@push('scripts')
    <script>
        $('.unread_notification').on('click', function() {
            $('.all_notification_div').addClass('d-none');
            $('.unread_notification_div').removeClass('d-none');
            $('.all_notification').removeClass('text-primary');
            $('.unread_notification').addClass('text-primary');

        });
        $('.all_notification').on('click', function() {
            $('.all_notification_div').removeClass('d-none');
            $('.unread_notification_div').addClass('d-none');
            $('.all_notification').addClass('text-primary');
            $('.unread_notification').removeClass('text-primary');

        })

        $(document).ready(function() {
            $('.ago').each(function() {
                var datetime = $(this).text();
                var momentDate = moment(datetime, "YYYY-MM-DD HH:mm:ss");
                $(this).text(momentDate.fromNow());
            })
            $('.ago_2').each(function() {
                var datetime = $(this).text();
                var momentDate = moment(datetime, "YYYY-MM-DD HH:mm:ss");
                $(this).text(momentDate.fromNow());
                //   console.log(time,'time');
            })
        })
    </script>
@endpush
