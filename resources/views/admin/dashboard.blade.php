@extends('admin.layouts.main')
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card card-modern">
                        <div class="card-body">
                            <h1 class="card-title">No. Of Users</h1>
                            <h2 class="card-numbers">
                                {{ \App\Helpers\GeneralHelper::getUserCount() }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-modern">
                        <div class="card-body">
                            <h1 class="card-title">No. Of Subscribers</h1>
                            <h2 class="card-numbers">
                                {{ \App\Helpers\GeneralHelper::getSubscriberCount() }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-modern">
                        <div class="card-body">
                            <h1 class="card-title">No. Of Blogs</h1>
                            <h2 class="card-numbers">
                                {{ \App\Helpers\GeneralHelper::getBlogCount() }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-modern">
                        <div class="card-body">
                            <h1 class="card-title">No. Of Events</h1>
                            <h2 class="card-numbers">
                                {{ \App\Helpers\GeneralHelper::getEventCount() }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>

    </main>
@endsection
