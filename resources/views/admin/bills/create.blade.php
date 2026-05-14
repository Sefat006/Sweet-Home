@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Generate Bill</h2>
                        <p style="color:white !important;" class="mb-0">
                            Flat: <strong>{{ $flat->flat_name }}</strong>
                        </p>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <a href="{{ route('admin.bills.index', [$building->id, $flat->id]) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Bills
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="form-vertical__item bg-style mb-30">
                <form action="{{ route('admin.bills.store', [$building->id, $flat->id]) }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Bill Month <span class="text-danger">*</span></label>
                                <input type="month" name="bill_month" value="{{ old('bill_month', $suggestedMonth) }}" class="form-control" required>
                                @error('bill_month') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group">
                                <label>Previous Due</label>
                                <input type="number" name="previous_due" value="{{ old('previous_due', $previousDue) }}" class="form-control" step="0.01" min="0">
                                @error('previous_due') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">Rent Breakdown</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input__group">
                                <label>House Rent</label>
                                <input type="number" name="house_rent" value="{{ old('house_rent', $flat->house_rent) }}" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input__group">
                                <label>WASA</label>
                                <input type="number" name="wasa" value="{{ old('wasa', $flat->wasa) }}" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input__group">
                                <label>Common Electricity</label>
                                <input type="number" name="common_electricity" value="{{ old('common_electricity', $flat->common_electricity) }}" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input__group">
                                <label>Gas</label>
                                <input type="number" name="gas" value="{{ old('gas', $flat->gas) }}" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input__group">
                                <label>Utility</label>
                                <input type="number" name="utility" value="{{ old('utility', $flat->utility) }}" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input__group">
                                <label>Parking</label>
                                <input type="number" name="parking" value="{{ old('parking', $flat->parking) }}" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input__group">
                                <label>Society Bill</label>
                                <input type="number" name="society_bill" value="{{ old('society_bill', $flat->society_bill) }}" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input__group">
                                <label>Security</label>
                                <input type="number" name="security" value="{{ old('security', $flat->security) }}" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input__group">
                                <label>Other</label>
                                <input type="number" name="other" value="{{ old('other', $flat->other) }}" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="input__group mb-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Any additional notes">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-blue">Generate Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
