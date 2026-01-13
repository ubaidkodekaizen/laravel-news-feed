@extends('admin.layouts.main')
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
<style>
    body{
        background: #fafbff !important;
    }

    .card-header:first-child {
        background: #fafbff !important;
        border: none;
    }

    .card-body {
        background: #fafbff !important;
    }

    .card-title {
        border-radius: 0;
        color: #333 !important;
        margin: 0;
        font-size: 28px;
        font-family: "Inter";
        font-weight: 500;
    }

    .detail-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .detail-label {
        font-weight: 600;
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
        font-family: 'Inter';
    }

    .detail-value {
        font-size: 16px;
        color: #333;
        font-family: 'Inter';
        word-break: break-word;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }
    .status-success {
        background: #d4edda;
        color: #155724;
    }
    .status-failed {
        background: #f8d7da;
        color: #721c24;
    }
    .status-partial {
        background: #fff3cd;
        color: #856404;
    }

    .code-block {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 15px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        white-space: pre-wrap;
        word-wrap: break-word;
        max-height: 400px;
        overflow-y: auto;
        margin-top: 10px;
    }

    .back-button {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
        font-family: 'Poppins';
    }

    .back-button:hover {
        background-color: #2d3a6b;
        border-color: #2d3a6b;
        color: white;
    }
</style>
@section('content')
    <main class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('admin.scheduler-logs') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Back to Scheduler Logs
                    </a>

                    <div class="card" style="border: none;">
                        <div class="card-header">
                            <h4 class="card-title">Scheduler Log Details</h4>
                        </div>
                        <div class="card-body">
                            <!-- Basic Information -->
                            <div class="detail-card">
                                <h5 style="font-family: 'Inter'; font-weight: 600; color: #333; margin-bottom: 20px; border-bottom: 2px solid #E1E0E0; padding-bottom: 10px;">Basic Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">ID</div>
                                        <div class="detail-value">{{ $log->id }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Status</div>
                                        <div class="detail-value">
                                            <span class="status-badge status-{{ $log->status }}">
                                                {{ strtoupper($log->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Scheduler</div>
                                        <div class="detail-value">{{ $log->scheduler }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Command</div>
                                        <div class="detail-value">{{ $log->command }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Execution Details -->
                            <div class="detail-card">
                                <h5 style="font-family: 'Inter'; font-weight: 600; color: #333; margin-bottom: 20px; border-bottom: 2px solid #E1E0E0; padding-bottom: 10px;">Execution Details</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="detail-label">Records Processed</div>
                                        <div class="detail-value">{{ $log->records_processed ?? 0 }}</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="detail-label">Records Updated</div>
                                        <div class="detail-value">{{ $log->records_updated ?? 0 }}</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="detail-label">Records Failed</div>
                                        <div class="detail-value">{{ $log->records_failed ?? 0 }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Ran At</div>
                                        <div class="detail-value">{{ \Carbon\Carbon::parse($log->ran_at)->format('d M Y h:i A') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Changes Summary -->
                            @if($log->result_data && isset($log->result_data['platforms']))
                            <div class="detail-card">
                                <h5 style="font-family: 'Inter'; font-weight: 600; color: #333; margin-bottom: 20px; border-bottom: 2px solid #E1E0E0; padding-bottom: 10px;">User Changes Summary</h5>
                                
                                @php
                                    $resultData = $log->result_data;
                                    $platforms = ['web' => 'Web/Authorize.Net', 'google' => 'Google Play', 'apple' => 'Apple'];
                                @endphp
                                
                                @foreach(['web', 'google', 'apple'] as $platform)
                                    @if(isset($resultData['platforms'][$platform]))
                                        @php
                                            $platformData = $resultData['platforms'][$platform];
                                            $users = $platformData['users'] ?? [];
                                            $renewed = $users['renewed'] ?? [];
                                            $updated = $users['updated'] ?? [];
                                            $cancelled = $users['cancelled'] ?? [];
                                        @endphp
                                        
                                        @if(count($updated) > 0 || count($cancelled) > 0 || count($renewed) > 0)
                                        <div style="margin-bottom: 30px;">
                                            <h6 style="font-family: 'Inter'; font-weight: 600; color: #37488E; margin-bottom: 15px; font-size: 18px;">
                                                {{ $platforms[$platform] }}
                                            </h6>
                                            
                                            @if(count($renewed) > 0)
                                            <div style="margin-bottom: 20px;">
                                                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                                    <span style="background: #28a745; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; margin-right: 10px;">
                                                        RENEWED ({{ count($renewed) }})
                                                    </span>
                                                </div>
                                                <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; padding: 15px;">
                                                    <table style="width: 100%; border-collapse: collapse;">
                                                        <thead>
                                                            <tr style="border-bottom: 2px solid #dee2e6;">
                                                                <th style="padding: 10px; text-align: left; font-family: 'Inter'; font-weight: 600; color: #333; font-size: 14px;">User</th>
                                                                <th style="padding: 10px; text-align: left; font-family: 'Inter'; font-weight: 600; color: #333; font-size: 14px;">Email</th>
                                                                <th style="padding: 10px; text-align: left; font-family: 'Inter'; font-weight: 600; color: #333; font-size: 14px;">Next Renewal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($renewed as $user)
                                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                                <td style="padding: 10px; font-family: 'Inter'; color: #333;">{{ $user['name'] ?? 'N/A' }}</td>
                                                                <td style="padding: 10px; font-family: 'Inter'; color: #666;">{{ $user['email'] ?? 'N/A' }}</td>
                                                                <td style="padding: 10px; font-family: 'Inter'; color: #333;">
                                                                    @if(isset($user['renewal_date']))
                                                                        {{ \Carbon\Carbon::parse($user['renewal_date'])->format('d M Y') }}
                                                                    @else
                                                                        N/A
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if(count($updated) > 0)
                                            <div style="margin-bottom: 20px;">
                                                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                                    <span style="background: #007bff; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; margin-right: 10px;">
                                                        UPDATED ({{ count($updated) }})
                                                    </span>
                                                </div>
                                                <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; padding: 15px;">
                                                    <table style="width: 100%; border-collapse: collapse;">
                                                        <thead>
                                                            <tr style="border-bottom: 2px solid #dee2e6;">
                                                                <th style="padding: 10px; text-align: left; font-family: 'Inter'; font-weight: 600; color: #333; font-size: 14px;">User</th>
                                                                <th style="padding: 10px; text-align: left; font-family: 'Inter'; font-weight: 600; color: #333; font-size: 14px;">Email</th>
                                                                <th style="padding: 10px; text-align: left; font-family: 'Inter'; font-weight: 600; color: #333; font-size: 14px;">Renewal Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($updated as $user)
                                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                                <td style="padding: 10px; font-family: 'Inter'; color: #333;">{{ $user['name'] ?? 'N/A' }}</td>
                                                                <td style="padding: 10px; font-family: 'Inter'; color: #666;">{{ $user['email'] ?? 'N/A' }}</td>
                                                                <td style="padding: 10px; font-family: 'Inter'; color: #333;">
                                                                    @if(isset($user['renewal_date']))
                                                                        {{ \Carbon\Carbon::parse($user['renewal_date'])->format('d M Y') }}
                                                                    @else
                                                                        N/A
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if(count($cancelled) > 0)
                                            <div style="margin-bottom: 20px;">
                                                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                                    <span style="background: #dc3545; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; margin-right: 10px;">
                                                        CANCELLED ({{ count($cancelled) }})
                                                    </span>
                                                </div>
                                                <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; padding: 15px;">
                                                    <table style="width: 100%; border-collapse: collapse;">
                                                        <thead>
                                                            <tr style="border-bottom: 2px solid #dee2e6;">
                                                                <th style="padding: 10px; text-align: left; font-family: 'Inter'; font-weight: 600; color: #333; font-size: 14px;">User</th>
                                                                <th style="padding: 10px; text-align: left; font-family: 'Inter'; font-weight: 600; color: #333; font-size: 14px;">Email</th>
                                                                <th style="padding: 10px; text-align: left; font-family: 'Inter'; font-weight: 600; color: #333; font-size: 14px;">Reason</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($cancelled as $user)
                                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                                <td style="padding: 10px; font-family: 'Inter'; color: #333;">{{ $user['name'] ?? 'N/A' }}</td>
                                                                <td style="padding: 10px; font-family: 'Inter'; color: #666;">{{ $user['email'] ?? 'N/A' }}</td>
                                                                <td style="padding: 10px; font-family: 'Inter'; color: #dc3545;">
                                                                    {{ $user['reason'] ?? 'Cancelled' }}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    @endif
                                @endforeach
                                
                                @if(!isset($resultData['platforms']['web']['users']) && !isset($resultData['platforms']['google']['users']) && !isset($resultData['platforms']['apple']['users']))
                                <div style="color: #666; font-family: 'Inter'; font-style: italic;">No user changes recorded for this sync.</div>
                                @endif
                            </div>
                            @endif


                            <!-- Error Information -->
                            @if($log->error_message || $log->error_trace)
                            <div class="detail-card">
                                <h5 style="font-family: 'Inter'; font-weight: 600; color: #333; margin-bottom: 20px; border-bottom: 2px solid #E1E0E0; padding-bottom: 10px;">Error Information</h5>
                                @if($log->error_message)
                                <div class="mb-3">
                                    <div class="detail-label">Error Message</div>
                                    <div class="alert alert-danger" style="margin-top: 5px;">{{ $log->error_message }}</div>
                                </div>
                                @endif
                                @if($log->error_trace)
                                <div>
                                    <div class="detail-label">Error Trace</div>
                                    <div class="code-block">{{ $log->error_trace }}</div>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Timestamps -->
                            <div class="detail-card">
                                <h5 style="font-family: 'Inter'; font-weight: 600; color: #333; margin-bottom: 20px; border-bottom: 2px solid #E1E0E0; padding-bottom: 10px;">Timestamps</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Created At</div>
                                        <div class="detail-value">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y h:i A') }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Updated At</div>
                                        <div class="detail-value">{{ \Carbon\Carbon::parse($log->updated_at)->format('d M Y h:i A') }}</div>
                                    </div>
                                    @if($log->deleted_at)
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Deleted At</div>
                                        <div class="detail-value">{{ \Carbon\Carbon::parse($log->deleted_at)->format('d M Y h:i A') }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
