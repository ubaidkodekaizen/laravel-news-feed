@extends('admin.layouts.main')

@section('content')
<style>
    body {
        background: #fafbff !important;
    }

    .card {
        border: 0 !important;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }

    .card:last-child {
        margin-bottom: 0;
    }

    .card-header {
        background: #fafbff !important;
        border: none;
        padding: 24px 35px;
        border-bottom: 1px solid #e9ecef;
    }

    @media (max-width: 768px) {
        .card-header {
            padding: 20px 15px;
        }
    }

    .card-body {
        background: #fff;
        padding: 30px 35px;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 20px 15px;
        }
    }

    .card-title {
        color: #333 !important;
        margin: 0;
        font-size: 28px;
        font-family: "Inter";
        font-weight: 600;
    }

    .detail-section {
        margin-bottom: 35px;
    }

    .detail-section:last-of-type {
        margin-bottom: 0;
    }

    .detail-section-title {
        font-family: 'Inter';
        font-size: 18px;
        font-weight: 600;
        color: #273572;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #273572;
    }

    .detail-row {
        display: flex;
        padding: 14px 0;
        border-bottom: 1px solid #f0f0f0;
        align-items: flex-start;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-family: 'Inter';
        font-weight: 600;
        color: #696969;
        width: 220px;
        flex-shrink: 0;
        font-size: 14px;
    }

    .detail-value {
        font-family: 'Inter';
        color: #333;
        flex: 1;
        font-size: 14px;
        word-wrap: break-word;
    }

    @media (max-width: 768px) {
        .detail-row {
            flex-direction: column;
        }
        
        .detail-label {
            width: 100%;
            margin-bottom: 5px;
        }
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-cancelled,
    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-expired {
        background-color: #fff3cd;
        color: #856404;
    }

    .event-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        text-transform: capitalize;
    }

    .event-created {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .event-renewed {
        background-color: #d4edda;
        color: #155724;
    }

    .event-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }

    .event-expired {
        background-color: #fff3cd;
        color: #856404;
    }

    .event-suspended {
        background-color: #ffeaa7;
        color: #d63031;
    }

    .btn-back {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-family: 'Inter';
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-back:hover {
        background: #273572;
        border-color: #273572;
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    .btn-back i {
        font-size: 14px;
    }

    table {
        font-family: 'Inter';
        font-size: 14px;
    }

    table thead th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding: 12px 15px;
        vertical-align: middle;
    }

    table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    table tbody tr:last-child td {
        border-bottom: none;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }

    .receipt-data {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        overflow-x: auto;
        font-size: 13px;
        font-family: 'Courier New', monospace;
        max-height: 400px;
        overflow-y: auto;
        word-wrap: break-word;
        white-space: pre-wrap;
        line-height: 1.6;
    }

    .receipt-data pre {
        margin: 0;
        white-space: pre-wrap;
        word-wrap: break-word;
        color: #495057;
    }

    .alert {
        border-radius: 8px;
        padding: 16px 20px;
        margin: 0;
        font-family: 'Inter';
    }

    .alert-info {
        background-color: #e7f3ff;
        border-color: #b8daff;
        color: #004085;
    }

    .alert-info i {
        margin-right: 8px;
    }
</style>

<main class="main-content">
    <div class="container" style="max-width: 1200px; padding: 20px 15px;">
        <div style="margin-bottom: 24px;">
            <a href="{{ route('admin.subscriptions') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Subscriptions
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Subscription Details - #{{ $subscription->id }}</h4>
            </div>
            <div class="card-body">
                <!-- User Information -->
                <div class="detail-section">
                    <div class="detail-section-title">User Information</div>
                    <div class="detail-row">
                        <div class="detail-label">Name:</div>
                        <div class="detail-value">
                            {{ $subscription->user ? trim($subscription->user->first_name . ' ' . $subscription->user->last_name) : 'N/A' }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Email:</div>
                        <div class="detail-value">{{ $subscription->user->email ?? 'N/A' }}</div>
                    </div>
                    @if($subscription->user && $subscription->user->phone)
                    <div class="detail-row">
                        <div class="detail-label">Phone:</div>
                        <div class="detail-value">{{ $subscription->user->phone }}</div>
                    </div>
                    @endif
                </div>

                <!-- Subscription Information -->
                <div class="detail-section">
                    <div class="detail-section-title">Subscription Information</div>
                    <div class="detail-row">
                        <div class="detail-label">Status:</div>
                        <div class="detail-value">
                            <span class="status-badge status-{{ strtolower($subscription->status ?? 'N/A') }}">
                                {{ ucfirst($subscription->status ?? 'N/A') }}
                            </span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Type:</div>
                        <div class="detail-value">{{ $subscription->subscription_type ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Amount:</div>
                        <div class="detail-value">
                            ${{ number_format($subscription->subscription_amount ?? 0, 2) }}
                        </div>
                    </div>
                    @if($subscription->platform)
                    <div class="detail-row">
                        <div class="detail-label">Platform:</div>
                        <div class="detail-value">{{ $subscription->platform }}</div>
                    </div>
                    @endif
                    @if($subscription->plan && $subscription->plan->plan_name)
                    <div class="detail-row">
                        <div class="detail-label">Plan:</div>
                        <div class="detail-value">{{ $subscription->plan->plan_name }}</div>
                    </div>
                    @endif
                    @if($subscription->transaction_id)
                    <div class="detail-row">
                        <div class="detail-label">Transaction ID:</div>
                        <div class="detail-value" style="word-break: break-all;">{{ $subscription->transaction_id }}</div>
                    </div>
                    @endif
                </div>

                <!-- Dates & Timeline -->
                <div class="detail-section">
                    <div class="detail-section-title">Dates & Timeline</div>
                    <div class="detail-row">
                        <div class="detail-label">Start Date:</div>
                        <div class="detail-value">
                            {{ $subscription->start_date ? \Carbon\Carbon::parse($subscription->start_date)->format('F d, Y') : 'N/A' }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Renewal Date:</div>
                        <div class="detail-value">
                            {{ $subscription->renewal_date ? \Carbon\Carbon::parse($subscription->renewal_date)->format('F d, Y') : 'N/A' }}
                        </div>
                    </div>
                    @if($subscription->expires_at)
                    <div class="detail-row">
                        <div class="detail-label">Expires At:</div>
                        <div class="detail-value">
                            {{ \Carbon\Carbon::parse($subscription->expires_at)->format('F d, Y') }}
                        </div>
                    </div>
                    @endif
                    @if($subscription->cancelled_at)
                    <div class="detail-row">
                        <div class="detail-label">Cancelled At:</div>
                        <div class="detail-value">
                            {{ \Carbon\Carbon::parse($subscription->cancelled_at)->format('F d, Y h:i A') }}
                        </div>
                    </div>
                    @endif
                    @if($subscription->last_renewed_at)
                    <div class="detail-row">
                        <div class="detail-label">Last Renewed At:</div>
                        <div class="detail-value">
                            {{ \Carbon\Carbon::parse($subscription->last_renewed_at)->format('F d, Y h:i A') }}
                        </div>
                    </div>
                    @endif
                    @if(isset($subscription->renewal_count) && $subscription->renewal_count > 0)
                    <div class="detail-row">
                        <div class="detail-label">Renewal Count:</div>
                        <div class="detail-value">{{ $subscription->renewal_count }}</div>
                    </div>
                    @endif
                </div>

                <!-- Additional Information -->
                <div class="detail-section">
                    <div class="detail-section-title">Additional Information</div>
                    <div class="detail-row">
                        <div class="detail-label">Auto Renewing:</div>
                        <div class="detail-value">{{ $subscription->auto_renewing ? 'Yes' : 'No' }}</div>
                    </div>
                    @if($subscription->payment_state)
                    <div class="detail-row">
                        <div class="detail-label">Payment State:</div>
                        <div class="detail-value">{{ $subscription->payment_state }}</div>
                    </div>
                    @endif
                    @if($subscription->grace_period_ends_at)
                    <div class="detail-row">
                        <div class="detail-label">Grace Period Ends At:</div>
                        <div class="detail-value">
                            {{ \Carbon\Carbon::parse($subscription->grace_period_ends_at)->format('F d, Y') }}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Receipt Data -->
                @if($subscription->receipt_data && !empty($subscription->receipt_data))
                @php
                    $receiptData = is_string($subscription->receipt_data) ? $subscription->receipt_data : $subscription->receipt_data;
                    if (is_array($receiptData)) {
                        $receiptData = json_encode($receiptData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    }
                @endphp
                @if($receiptData && $receiptData !== 'base64_receipt_string_here' && !empty(trim($receiptData)))
                <div class="detail-section">
                    <div class="detail-section-title">Receipt Data</div>
                    <div class="receipt-data">
                        <pre>{{ $receiptData }}</pre>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>

        <!-- Billing History Table -->
        <div class="card" style="margin-top: 0;">
            <div class="card-header">
                <h4 class="card-title">Billing History 
                    @if($totalPaid > 0)
                        <span style="font-size: 18px; color: #696969; font-weight: 400;">
                            (Total Paid: ${{ number_format($totalPaid, 2) }})
                        </span>
                    @endif
                </h4>
            </div>
            <div class="card-body" style="padding-top: 30px;">
                @if($billingHistory->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Event Type</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Transaction ID</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billingHistory as $billing)
                            <tr>
                                <td>
                                    <span class="event-badge event-{{ strtolower(str_replace('_', '-', $billing->event_type ?? 'billing')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $billing->event_type ?? 'Billing')) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $billing->event_date ? \Carbon\Carbon::parse($billing->event_date)->format('M d, Y') : ($billing->billing_date ? \Carbon\Carbon::parse($billing->billing_date)->format('M d, Y') : 'N/A') }}
                                </td>
                                <td>
                                    @if($billing->amount)
                                        ${{ number_format($billing->amount, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($billing->status)
                                        <span class="status-badge status-{{ strtolower($billing->status) }}">
                                            {{ ucfirst($billing->status) }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $billing->transaction_id ?? 'N/A' }}</td>
                                <td>{{ $billing->notes ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info" style="text-align: center;">
                    <i class="fas fa-info-circle"></i> No billing history found for this subscription. Run the sync command to populate billing history.
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
