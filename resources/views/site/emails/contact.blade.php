<h2>Contact Request</h2>
The following information was submitted via the Contact Request form:<br />
<br />
<hr />
<table>
    <tr>
        <td width="150px">Name:</td>
        <td>{{ $name }}</td>
    </tr>
    <tr>
        <td>Company Name:</td>
        <td>{{ $company }}</td>
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
        <td valign="top">Message:</td>
        <td>{{ $message }}</td>
    </tr>
</table>
<br />
<hr />
<?php if(isset($logo) && $logo != '') { ?>
    <a href="{{ $root }}"><img src="{{ $logo }}" alt="{{ $site }}" border="0" style="margin: 5px 0;" /></a><br />
<?php } ?>
<a href="{{ $root }}">{{ $root }}</a><br />
<br /><small><em><font color="#999999">This e-mail is auto generated.  Please do not respond.</em></font></small><br />
<br />