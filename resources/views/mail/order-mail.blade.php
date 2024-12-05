@include('mail.EmailHeadFormat')
            <h1>Hi</h1>
            <p>
                We are pleased to inform you that a quotation has been received from {{$company}}.
            </p>
            <p>Please log in to the purchasing system to review the quotation at your earliest convenience, or click the link below to view it:</p>
            <p>
                <a href="{{$quotationUrl}}" target="_blank">View Quotation</a>
            </p>
            <p>Thank you,</p>
@include('mail.EmailFooterFormat')     