<h2>Business or Corporate Referral</h2>
The following information was submitted via the Referrals form:<br />
<br />
<hr />
<table>
    <tr>
        <td width="225px">Name:</td>
        <td>{{ $name }}</td>
    </tr>
    <tr>
        <td>Email:</td>
        <td>{{ $cemail }}</td>
    </tr>
    <tr>
        <td>Phone:</td>
        <td>{{ $phone }}</td>
    </tr>
    <tr>
        <td>Referred Company Name:</td>
        <td>{{ $company }}</td>
    </tr>
    <tr>
        <td>Owner or Decision Maker:</td>
        <td>{{ $owner }}</td>
    </tr>
    <tr>
        <td>Relationship to Referrer:</td>
        <td>{{ $relationship }}</td>
    </tr>
    <tr>
        <td>Email Address or Phone Number:</td>
        <td>{{ $contact }}</td>
    </tr>
    @if (isset($additional_information))
    <tr>
        <td valign="top">Additional Information:</td>
        <td>{{ $additional_information }}</td>
    </tr>
    @endif
</table>
<br />
<hr />
<?php if(isset($logo) && $logo != '') { ?>
    <a href="{{ $root }}"><img src="{{ $logo }}" alt="{{ $site }}" border="0" style="margin: 5px 0;" /></a><br />
<?php } ?>
<a href="{{ $root }}">{{ $root }}</a><br />
<br /><small><em><font color="#999999">This e-mail is auto generated.  Please do not respond.</em></font></small><br />
<br />