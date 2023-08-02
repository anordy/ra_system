@component('mail::message')
# Hello {{ $certificate->business->taxpayer->first_name }},

Your quantity of Certificate with number {{ $certificate->certificate_no }} has been generated. 

Please login to the system to verify the details of petroleum products. If the certificate is correct no action has to be taken from your end other than filing the associated return if not please reject the certificate in the system by following the below instructions after logging into the system:

1. Click on Quantity of Certificate Menu item on the sidebar then click Certificates
2. Click View & Approve button to view contents of the certificate generated
3. If the product items are incorrect, fill in the comments describing wrong products the click on "Reject Certificate of Quantity"
4. If the products are correct click on "Accept" to accept quantity of certificate

NOTE: Failure to accept the quantity of certificate will result to failure to file your return

Thanks,<br>
{{ config('app.name') }}
@endcomponent
