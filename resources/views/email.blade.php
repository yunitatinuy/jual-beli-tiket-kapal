<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Email Send Using PHPMailer - webappfix.com</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5" style="max-width: 750px">

        <h1>Email Send Using PHPMailer - webappfix.com</h1>

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible">
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <form method="post" action="{{ route('send.email.post') }}">
            @csrf
            <div class="form-group">
                <label>Recipient Email:</label>
                <input type="email" name="email" class="form-control" />
            </div>
            <div class="form-group">
                <label>Email Subject:</label>
                <input type="text" name="subject" class="form-control" />
            </div>
            <div class="form-group">
                <label>Email Body:</label>
                <textarea class="form-control" name="body"></textarea>
            </div>
            <div class="mt-3 mb-3 form-group">
                <button type="submit" class="btn btn-success btn-block">Send Email</button>
            </div>
        </form>
    </div>
</body>

</html>


{{-- @extends('layout.main2')

@section('container')
    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="m-auto col-xl-6 col-lg-6 col-sm-12 col-12">
                <form action="{{ route('send-email') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="shadow card"> +
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible"><button type="button"
                                    class="close">&times;</button>{{ Session::get('success') }}</div>
                        @elseif(Session::has('failed'))
                            <div class="alert alert-danger alert-dismissible"><button type="button"
                                    class="close">&times;</button>{{ Session::get('failed') }}</div>
                        @endif

                        <div class="card-header">
                            <h4 class="card-title">Email Testing Tutorial</h4>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="emailRecipient">Email To </label>
                                <input type="email" name="emailRecipient" id="emailRecipient" class="form-control"
                                    placeholder="Mail To">
                            </div>

                            <div class="form-group">
                                <label for="emailCc">CC </label>
                                <input type="email" name="emailCc" id="emailCc" class="form-control"
                                    placeholder="Mail CC">
                            </div>

                            <div class="form-group">
                                <label for="emailBcc">BCC </label>
                                <input type="email" name="emailBcc" id="emailBcc" class="form-control"
                                    placeholder="Mail BCC">
                            </div>

                            <div class="form-group">
                                <label for="emailSubject">Subject </label>
                                <input type="text" name="emailSubject" id="emailSubject" class="form-control"
                                    placeholder="Mail Subject">
                            </div>

                            <div class="form-group">
                                <label for="emailBody">Message </label>
                                <textarea name="emailBody" id="emailBody" class="form-control" placeholder="Mail Body"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="emailAttachments">Attachment(s) </label>
                                <input type="file" name="emailAttachments[]" multiple="multiple" id="emailAttachments"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Send Email </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush --}}
