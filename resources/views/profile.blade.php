@extends('layouts.master');
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Edit Profile</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 pr-md-1">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6 pl-md-1">
                            <div class="form-group">
                                <label>Email address</label>
                                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-md-1">
                            <div class="form-group">
                                <label>Password (leave blank to keep current)</label>
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="col-md-6 pl-md-1">
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Home Address" value="{{ old('address', $user->address) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 pr-md-1">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" class="form-control" name="city" placeholder="City" value="{{ old('city', $user->city) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 px-md-1">
                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" class="form-control" name="country" placeholder="Country" value="{{ old('country', $user->country) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 pl-md-1">
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="number" class="form-control" name="postal_code" placeholder="ZIP Code" value="{{ old('postal_code', $user->postal_code) }}" required>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->role !=='admin')
                    <!-- Bank Details Section -->
                    <h4>Bank Details</h4>
                    <div class="row">
                        <div class="col-md-6 pr-md-1">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control" name="bank_full_name" placeholder="Account Holder's Name" value="{{ old('bank_full_name', $user->full_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 pl-md-1">
                            <div class="form-group">
                                <label>Bank Name</label>
                                <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="{{ old('bank_name', $user->bank_name) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-md-1">
                            <div class="form-group">
                                <label>IBAN</label>
                                <input type="text" class="form-control" name="iban" placeholder="IBAN" value="{{ old('iban', $user->iban) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 pl-md-1">
                            <div class="form-group">
                                <label>SWIFT/BIC Code</label>
                                <input max=11 type="text" class="form-control" name="swift_code" placeholder="SWIFT/BIC Code" value="{{ old('swift_code', $user->swift_code) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-md-1">
                            <div class="form-group">
                                <label>Account Number</label>
                                <input type="text" class="form-control" name="account_number" placeholder="Account Number" value="{{ old('account_number', $user->account_number) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 pl-md-1">
                            <!-- <div class="form-group">
                                <label>Branch Code (Optional)</label>
                                <input type="text" class="form-control" name="branch_code" placeholder="Branch Code" value="{{ old('branch_code', $user->branch_code) }}">
                            </div> -->
                        </div>
                    </div>
                    @endif

                    <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>About Me</label>
                                <textarea rows="4" cols="80" class="form-control" name="about" placeholder="Here can be your description">{{ old('about', $user->about) }}</textarea>
                            </div>
                        </div>
                    </div> -->
                    <button type="submit" class="btn btn-fill btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionCount = 1;

    $('#datatable').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records",
        }

    });

    document.getElementById('add-question').addEventListener('click', function() {
        let questionHTML = `
            <div class="question mb-4">
                <label for="question_text">Question ${questionCount + 1}</label>
                <input type="text" name="questions[${questionCount}][question_text]" class="form-control" placeholder="Enter question" required>
                <h5>Options</h5>
                <div class="options-container">
                    <input type="text" name="questions[${questionCount}][options][]" class="form-control mb-2" placeholder="Enter option" required>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary add-option">Add Option</button>
            </div>
        `;

        document.getElementById('questions-container').insertAdjacentHTML('beforeend', questionHTML);
        questionCount++;
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('add-option')) {
            let optionsContainer = e.target.previousElementSibling;
            let optionHTML =
                `<input type="text" name="${optionsContainer.querySelector('input').name}" class="form-control mb-2" placeholder="Enter option" required>`;
            optionsContainer.insertAdjacentHTML('beforeend', optionHTML);
        }
    });
});
</script>
@endpush