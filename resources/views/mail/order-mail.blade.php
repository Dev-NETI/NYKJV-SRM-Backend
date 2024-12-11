@include('mail.EmailHeadFormat')
            {!!$emailBody!!}
            <p>Please log in to the purchasing system to review the quotation at your earliest convenience, or click the link below to view it:</p>
            <p>
                <a href="{{$quotationUrl}}" target="_blank">View Quotation</a>
            </p>
            <p>Thank you,</p>
@include('mail.EmailFooterFormat')     