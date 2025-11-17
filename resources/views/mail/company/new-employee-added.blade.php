@component('mail::message')
<div class="text-12">
<p>Dear Admin,</p>
<p>New employee has been added. The employee details are:</p>

<table class="table table-mail" style="width:100%;">
<tr valign="top">
<td width="47%"><b>First Name</b></td>
<td>:</td>
<td>{{ $employee->first_name }}</td>
</tr>

<tr valign="top">
<td width="47%"><b>Last Name</b></td>
<td>:</td>
<td>{{ $employee->last_name }}</td>
</tr>

<tr valign="top">
<td width="47%"><b>Email</b></td>
<td>:</td>
<td>{{ $employee->email ?? '-' }}</td>
</tr>

<tr valign="top">
<td width="47%"><b>Phone No.</b></td>
<td>:</td>
<td>{{ $employee->phone_no ?? '-' }}</td>
</tr>

<tr valign="top">
<td><b>Created At</b></td>
<td>:</td>
<td>{{ $employee->created_at }}</td>
</tr>
</table>
</div>
@endcomponent
