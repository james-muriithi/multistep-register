@extends("layouts.app")
@section('styles')

@endsection

@section('content')

    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-8 card shadow p-3">
                <h1 class="text-center">
                    Register
                </h1>

                <form class="" enctype="multipart/form-data">
                    @csrf
                    <div class="steps-wizard">
                        <div class="controls d-flex justify-content-around mt-4">
                            <div class="control">
                                <div>
                                    1
                                </div>
                            </div>
                            <div class="control">
                                <div>
                                    2
                                </div>
                            </div>
                            <div class="control">
                                <div>
                                    3
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="steps mt-5">
                        <div class="step step-one">
                            <h2 class="mb-3">Personal Details</h2>
                            <div class="form-group mb-3">
                                <label class="form-label" for="">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                                <div class="invalid-feedback">
                                    Please provide a valid first name
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                                <div class="invalid-feedback">
                                    Please provide a valid last name
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="">Mobile No</label>
                                <input type="text" name="phone" class="form-control" required>
                                <div class="invalid-feedback">
                                    Please provide a valid mobile number
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="">Id No</label>
                                <input type="text" name="id_no" class="form-control" required>
                                <div class="invalid-feedback">
                                    Please provide a valid id No
                                </div>
                            </div>

                            <div class="step-controls mt-4 text-end">
                                <button type="button" class="btn btn-primary next" data-next="1">Next</button>
                            </div>
                        </div>

                        <div class=" step step-two">
                            <h2 class="mb-3">Upload your files</h2>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Passport Photo (512px)</label>

                                <input type="file" name="passport" accept="image/*" class="form-control">
                                <div class="invalid-feedback">
                                    Please provide a valid photo
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Resume</label>

                                <input type="file" name="resume" class="form-control" accept="application/pdf""">
                                <div class="invalid-feedback">
                                    Please provide a valid resume
                                </div>
                            </div>
                            <div class="step-controls mt-4 row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary back" data-next="0">Back</button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-primary next" data-next="2">Next</button>
                                </div>
                            </div>
                        </div>

                        <div class="step step-three">
                            <div class="h-100 mt-3">
                                <h1 class="text-center">Thank you for signin up</h1>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function() {
            $(".next").on("click", function() {
                const nextStepIndex = $(this).data("next");
                const currentStep = $($('.step')[nextStepIndex - 1]);
                const nextStep = $($('.step')[nextStepIndex]);
                if (stepValidate(currentStep)) {
                    // if last step
                    if (nextStepIndex == 2) {
                        submitForm({
                            success: function(data) {
                                currentStep.hide().removeClass('d-block');
                                nextStep.addClass("d-block");
                                $('.control').addClass('active');
                                
                                toastr.success("You have signed up successfully");
                            },
                            error: function(response) {
                                if (response.responseJSON) {                                    
                                    $.each(response.responseJSON.errors, function(key, value) {
                                        toastr.error(value)
                                    });
                                }
                                
                            }
                        });
                        return;
                    }
                    currentStep.hide().removeClass('d-block');
                    nextStep.addClass("d-block");
                    $('.control').eq(nextStepIndex - 1).addClass('active');
                }
            })

            $(".back").on("click", function() {
                const nextStepIndex = $(this).data("next");
                const currentStep = $($('.step')[nextStepIndex + 1]);
                const nextStep = $($('.step')[nextStepIndex]);
                currentStep.hide().removeClass('d-block');
                nextStep.addClass("d-block");
            });


            $("input[type='file']").on("change", function() {
                $(this).removeClass("is-invalid");
            });
            $("input").on("keyup", function() {
                $(this).removeClass("is-invalid");
            })
        });

        function stepValidate(currentStep) {
            let valid = true;
            currentStep.find("input").each(function() {
                if (!$(this).val()) {
                    $(this).addClass("is-invalid");
                    valid = false;
                }
            })
            return valid;
        }

        function submitForm({
            success,
            error
        }) {
            const form = $('form')[0],
                formData = new FormData(form);

            $.ajax({
                type: "POST",
                url: '{{ route('register.create') }}',
                data: formData,
                processData: false,
                contentType: false,
                success,
                error
            });
        }
    </script>
@endsection
