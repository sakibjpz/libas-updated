@extends('layouts.app')

@section('title', 'Track Your Parcel - LibasBD')
@section('description', 'আপনার LibasBD অর্ডারের পার্সেল কোথায় আছে জানুন — Tracking Code বা Invoice নম্বর দিয়ে লাইভ স্ট্যাটাস দেখুন।')
@section('robots', 'noindex, follow')

@section('content')
<div class="track-page">
    <div class="track-card">
        <div class="track-head">
            <i class="fas fa-box-open"></i>
            <h1>পার্সেল ট্র্যাক করুন</h1>
            <p>আপনার Tracking Code, Invoice নম্বর অথবা Consignment ID দিন</p>
        </div>

        <form method="GET" action="{{ route('track') }}" class="track-form">
            <input type="text" name="code" value="{{ $query }}"
                   placeholder="Order ID (যেমন: 25), Tracking Code অথবা Invoice"
                   required autofocus>
            <button type="submit"><i class="fas fa-magnifying-glass"></i> ট্র্যাক করুন</button>
        </form>

        @if($order)
            @php
                $orderStatusMap = [
                    'pending'    => ['পেন্ডিং — রিভিউ চলছে', '#d97706'],
                    'confirmed'  => ['কনফার্ম হয়েছে', '#2563eb'],
                    'processing' => ['প্রসেসিং চলছে', '#2563eb'],
                    'shipped'    => ['কুরিয়ারে পাঠানো হয়েছে', '#7c3aed'],
                    'delivered'  => ['ডেলিভারি সম্পন্ন', '#16a34a'],
                    'completed'  => ['সম্পন্ন', '#16a34a'],
                    'cancelled'  => ['বাতিল', '#dc2626'],
                    'returned'   => ['রিটার্ন হয়েছে', '#dc2626'],
                    'hold'       => ['হোল্ডে আছে', '#d97706'],
                ];
                [$oLabel, $oColor] = $orderStatusMap[strtolower($order->status)] ?? [ucfirst($order->status), '#4b5563'];
            @endphp
            <div class="track-result">
                <div class="track-order-head">
                    <span class="track-order-badge">অর্ডার #{{ $order->id }}</span>
                    <span class="track-order-status" style="background: {{ $oColor }}1a; color: {{ $oColor }}; border: 1px solid {{ $oColor }}55;">{{ $oLabel }}</span>
                </div>
                <div class="track-grid">
                    <div><span>নাম</span><b>{{ $order->customer_name }}</b></div>
                    <div><span>মোট</span><b>৳{{ number_format((float) $order->total) }}</b></div>
                    <div><span>অর্ডারের তারিখ</span><b>{{ $order->created_at->format('d M Y, h:i A') }}</b></div>
                    <div><span>কুরিয়ার</span><b>{{ $order->steadfast_consignment_id || $order->steadfast_tracking_code ? 'Steadfast' : 'এখনো পাঠানো হয়নি' }}</b></div>
                </div>
            </div>
        @endif

        @if($error)
            <div class="track-msg track-msg--error">
                <i class="fas fa-circle-exclamation"></i> {{ $error }}
            </div>
        @endif

        @if($result)
            @php
                $status = strtolower((string) ($result['delivery_status'] ?? 'pending'));
                $statusMap = [
                    'delivered'                    => ['ডেলিভারি সম্পন্ন', '#16a34a', 'fa-circle-check'],
                    'partial_delivered'            => ['আংশিক ডেলিভারি', '#16a34a', 'fa-circle-check'],
                    'delivered_approval_pending'   => ['ডেলিভারি সম্পন্ন', '#16a34a', 'fa-circle-check'],
                    'pending'                      => ['পেন্ডিং — পিকআপের অপেক্ষায়', '#d97706', 'fa-clock'],
                    'pickup_pending'               => ['পিকআপের অপেক্ষায়', '#d97706', 'fa-clock'],
                    'in_review'                    => ['রিভিউ চলছে', '#d97706', 'fa-magnifying-glass'],
                    'hold'                         => ['হোল্ডে আছে', '#d97706', 'fa-pause-circle'],
                    'cancelled'                    => ['বাতিল করা হয়েছে', '#dc2626', 'fa-circle-xmark'],
                    'returned'                     => ['রিটার্ন হয়েছে', '#dc2626', 'fa-rotate-left'],
                    'return_pending'               => ['রিটার্ন প্রসেসিং', '#d97706', 'fa-rotate-left'],
                    'return_completed'             => ['রিটার্ন সম্পন্ন', '#dc2626', 'fa-rotate-left'],
                    'paid'                         => ['ডেলিভার্ড ও পেমেন্ট সম্পন্ন', '#16a34a', 'fa-circle-check'],
                ];
                [$label, $color, $icon] = $statusMap[$status] ?? [ucwords(str_replace('_', ' ', $status)), '#4b5563', 'fa-truck'];
            @endphp

            <div class="track-result">
                <div class="track-status" style="--st-color: {{ $color }};">
                    <i class="fas {{ $icon }}"></i>
                    <div>
                        <strong>{{ $label }}</strong>
                        <span>{{ str_replace('_', ' ', $result['delivery_status'] ?? '') }}</span>
                    </div>
                </div>

                <div class="track-grid">
                    @if(!empty($result['tracking_code']))
                        <div><span>Tracking Code</span><b>{{ $result['tracking_code'] }}</b></div>
                    @endif
                    @if(!empty($result['consignment_id']))
                        <div><span>Consignment ID</span><b>{{ $result['consignment_id'] }}</b></div>
                    @endif
                    @if(!empty($result['invoice']))
                        <div><span>Invoice</span><b>{{ $result['invoice'] }}</b></div>
                    @endif
                    @if(!empty($result['recipient_name']))
                        <div><span>নাম</span><b>{{ $result['recipient_name'] }}</b></div>
                    @endif
                </div>
            </div>
        @endif

        <div class="track-help">
            <i class="fas fa-headset"></i>
            সমস্যা হলে কল করুন: <a href="tel:+8801333257604">01333-257604</a> (সকাল ৯টা - রাত ১০টা)
        </div>
    </div>
</div>

<style>
    .track-page { display: flex; justify-content: center; padding: 50px 16px 70px; }
    .track-card {
        width: 100%; max-width: 560px;
        background: #fff; border-radius: 18px;
        box-shadow: 0 10px 40px rgba(29,25,18,.10);
        border: 1px solid #efe8d8;
        overflow: hidden;
    }
    .track-head {
        text-align: center; padding: 34px 26px 24px;
        background: linear-gradient(135deg, #faf6ec, #fff);
        border-bottom: 1px solid #efe8d8;
    }
    .track-head i { font-size: 40px; color: #a07f2a; margin-bottom: 12px; }
    .track-head h1 { font-size: 24px; font-weight: 800; color: #1d1912; margin: 0 0 6px; }
    .track-head p { font-size: 14px; color: #6b6150; margin: 0; }

    .track-form { display: flex; gap: 10px; padding: 24px 26px 8px; }
    .track-form input {
        flex: 1; padding: 13px 16px; font-size: 15px;
        border: 2px solid #e9e2d0; border-radius: 10px;
        outline: none; transition: border-color .2s;
    }
    .track-form input:focus { border-color: #c9a24b; }
    .track-form button {
        background: linear-gradient(135deg, #d4b25f, #9c7c33);
        color: #fff; border: none; border-radius: 10px;
        padding: 13px 22px; font-size: 15px; font-weight: 700;
        cursor: pointer; white-space: nowrap;
        transition: all .2s; box-shadow: 0 4px 14px rgba(156,124,51,.3);
    }
    .track-form button:hover { transform: translateY(-2px); }

    .track-msg { margin: 14px 26px 0; padding: 12px 16px; border-radius: 10px; font-size: 14px; font-weight: 600; }
    .track-msg--error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    .track-result { padding: 18px 26px 6px; }
    .track-order-head { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:14px; }
    .track-order-badge { font-size:17px; font-weight:800; color:#1a1a1a; }
    .track-order-status { padding:5px 14px; border-radius:50px; font-size:13px; font-weight:700; }
    .track-status {
        display: flex; align-items: center; gap: 14px;
        background: #fafafa; border: 1px solid #eee; border-left: 5px solid var(--st-color);
        border-radius: 12px; padding: 16px 18px; margin-bottom: 14px;
    }
    .track-status i { font-size: 30px; color: var(--st-color); }
    .track-status strong { display: block; font-size: 17px; color: var(--st-color); }
    .track-status span { font-size: 12px; color: #6b7280; text-transform: capitalize; }

    .track-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .track-grid > div { background: #faf8f2; border: 1px solid #efe8d8; border-radius: 10px; padding: 12px 14px; }
    .track-grid span { display: block; font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 3px; }
    .track-grid b { font-size: 15px; color: #1d1912; }

    .track-help {
        margin-top: 20px; padding: 16px 26px;
        background: #faf6ec; border-top: 1px solid #efe8d8;
        font-size: 13px; color: #6b6150; text-align: center;
    }
    .track-help i { color: #a07f2a; margin-right: 4px; }
    .track-help a { color: #a07f2a; font-weight: 700; text-decoration: none; }

    @media (max-width: 480px) {
        .track-form { flex-direction: column; }
        .track-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection
