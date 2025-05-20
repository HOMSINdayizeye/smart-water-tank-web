@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Usage Recommendations</h2>
                </div>

                <div class="card-body">
                    @if($tank)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h3 class="h5 mb-0">Current Status</h3>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Water Level:</strong> {{ $tank->current_level }} / {{ $tank->capacity }} L</p>
                                        <p><strong>pH Level:</strong> {{ $tank->ph_level }}</p>
                                        <p><strong>Chloride Level:</strong> {{ $tank->chloride_level }} mg/L</p>
                                        <p><strong>Fluoride Level:</strong> {{ $tank->fluoride_level }} mg/L</p>
                                        <p><strong>Nitrate Level:</strong> {{ $tank->nitrate_level }} mg/L</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h3 class="h5 mb-0">Recommendations</h3>
                                    </div>
                                    <div class="card-body">
                                        @if($tank->ph_level < 6.5 || $tank->ph_level > 8.5)
                                            <div class="alert alert-warning">
                                                <strong>pH Level Alert:</strong> Your water's pH level is outside the recommended range (6.5-8.5). 
                                                Consider adjusting your water treatment system.
                                            </div>
                                        @endif

                                        @if($tank->chloride_level > 250)
                                            <div class="alert alert-warning">
                                                <strong>Chloride Level Alert:</strong> Your water's chloride level is above the recommended limit (250 mg/L).
                                                Consider installing a water softener or filtration system.
                                            </div>
                                        @endif

                                        @if($tank->fluoride_level > 1.5)
                                            <div class="alert alert-warning">
                                                <strong>Fluoride Level Alert:</strong> Your water's fluoride level is above the recommended limit (1.5 mg/L).
                                                Consider installing a fluoride removal system.
                                            </div>
                                        @endif

                                        @if($tank->nitrate_level > 50)
                                            <div class="alert alert-warning">
                                                <strong>Nitrate Level Alert:</strong> Your water's nitrate level is above the recommended limit (50 mg/L).
                                                Consider installing a nitrate removal system.
                                            </div>
                                        @endif

                                        @if($tank->current_level < ($tank->capacity * 0.2))
                                            <div class="alert alert-danger">
                                                <strong>Low Water Level:</strong> Your tank is running low on water (below 20% capacity).
                                                Consider refilling soon to ensure continuous supply.
                                            </div>
                                        @endif

                                        @if(!$tank->ph_level < 6.5 && !$tank->ph_level > 8.5 && 
                                            !$tank->chloride_level > 250 && 
                                            !$tank->fluoride_level > 1.5 && 
                                            !$tank->nitrate_level > 50 && 
                                            !$tank->current_level < ($tank->capacity * 0.2))
                                            <div class="alert alert-success">
                                                <strong>All Good!</strong> Your water quality parameters are within recommended ranges.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="h5 mb-0">Maintenance Recommendations</h3>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        Schedule regular tank cleaning every 6 months
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-filter me-2"></i>
                                        Replace water filters every 3 months
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-tools me-2"></i>
                                        Inspect tank fittings and valves quarterly
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-chart-line me-2"></i>
                                        Monitor water quality parameters weekly
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            No tank information available. Please contact your administrator.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 