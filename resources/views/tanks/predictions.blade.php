@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-6">AI-Powered Predictions</h1>

    @if($tank)
        {{-- Water Level and Quality Predictions --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Water Level Predictions --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Water Level Predictions</h2>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 p-3 bg-blue-100 rounded-full">
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-700 mb-2">
                            Based on your current usage patterns, your tank will need refilling in approximately:
                        </p>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ $predictions['refill_days'] ?? 'N/A' }} days
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            This prediction is based on your average daily water consumption and current tank level.
                        </p>
                    </div>
                </div>
                {{-- Potential area for a historical level chart --}}
                <div class="mt-4 p-4 bg-gray-50 rounded-md text-gray-600 text-sm italic">
                   Placeholder for Water Level History Chart
                </div>
            </div>

            {{-- Quality Trend Predictions --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Quality Trend Predictions</h2>
                 <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 p-3 bg-green-100 rounded-full">
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-700 mb-2">
                            Based on historical data, your water quality parameters are expected to remain:
                        </p>
                        @php
                            $qualityTrend = $predictions['quality_trend'] ?? 'N/A';
                            $trendColor = 'text-gray-600';
                            if ($qualityTrend === 'improving') $trendColor = 'text-green-600';
                            elseif ($qualityTrend === 'declining') $trendColor = 'text-red-600';
                            elseif ($qualityTrend === 'stable') $trendColor = 'text-blue-600';
                        @endphp
                        <p class="text-3xl font-bold {{ $trendColor }}">
                            {{ ucfirst($qualityTrend) }}
                        </p>
                         <p class="text-gray-700 mt-1">
                            for the next <strong class="{{ $trendColor }}">{{ $predictions['quality_days'] ?? 'N/A' }} days</strong>.
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            Regular monitoring is still recommended to ensure water quality standards are maintained.
                        </p>
                    </div>
                </div>
                 {{-- Potential area for a quality trend chart --}}
                 <div class="mt-4 p-4 bg-gray-50 rounded-md text-gray-600 text-sm italic">
                    Placeholder for Water Quality Trend Chart
                 </div>
            </div>
        </div>

        {{-- Usage Pattern Analysis --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Usage Pattern Analysis</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-semibold text-lg text-gray-700">Peak Usage Time</h3>
                    <p class="text-blue-600 text-2xl font-bold mt-1">{{ $predictions['peak_usage_time'] ?? 'N/A' }}</p>
                     {{-- Potential area for a daily usage chart --}}
                    <div class="mt-3 p-3 bg-gray-100 rounded-md text-gray-600 text-xs italic">
                       Daily Usage Distribution Placeholder
                    </div>
                </div>
                <div class="p-4 bg-green-50 rounded-lg">
                    <h3 class="font-semibold text-lg text-gray-700">Average Daily Usage</h3>
                    <p class="text-green-600 text-2xl font-bold mt-1">{{ $predictions['average_daily_usage'] ?? 'N/A' }} L</p>
                    {{-- Potential area for a historical average usage chart --}}
                     <div class="mt-3 p-3 bg-gray-100 rounded-md text-gray-600 text-xs italic">
                       Historical Average Usage Placeholder
                    </div>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg">
                    <h3 class="font-semibold text-lg text-gray-700">Monthly Trend</h3>
                    @php
                         $monthlyTrend = $predictions['monthly_trend'] ?? 'N/A';
                         $trendColor = 'text-gray-600';
                         if ($monthlyTrend === 'Increasing') $trendColor = 'text-red-600';
                         elseif ($monthlyTrend === 'Decreasing') $trendColor = 'text-green-600';
                         elseif ($monthlyTrend === 'Stable') $trendColor = 'text-blue-600';
                     @endphp
                    <p class="{{ $trendColor }} text-2xl font-bold mt-1">{{ $monthlyTrend }}</p>
                    {{-- Potential area for a monthly usage trend chart --}}
                     <div class="mt-3 p-3 bg-gray-100 rounded-md text-gray-600 text-xs italic">
                       Monthly Usage Trend Placeholder
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p class="font-bold">No Tank Information</p>
            <p>Tank information is not available for predictions. Please ensure a tank is assigned to your account.</p>
        </div>
    @endif
</div>
@endsection 