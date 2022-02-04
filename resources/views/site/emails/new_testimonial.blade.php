<h2>New <em>{{ isset($private) ? 'Private' : 'Public' }}</em> Testimonial</h2>
The following testimonial has just been submitted:<br />
<br />
<hr />
<table>
    <tr>
        <td width="150px">Name:</td>
        <td>{{ $customer }}</td>
    </tr>
    @if ($cemail != '')
    <tr>
        <td>Email:</td>
        <td>{{ $cemail }}</td>
    </tr>
    @endif
    <tr>
        <td>Message:</td>
        <td>{{ $body }}</td>
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