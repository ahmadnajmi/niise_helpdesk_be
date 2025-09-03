{{-- <x-mail::message>
<br>
<br>
<table>
  <tr>
    <td style="white-space: nowrap;">Customer</td>
    <td style="white-space: nowrap;">:</td>
    <td style="white-space: nowrap;">{{ $incident->complaint?->name }}</td>
  </tr>
  <tr>
    <td style="white-space: nowrap;">Location</td>
    <td style="white-space: nowrap;">:</td>

    <td style="white-space: nowrap;">{{ $incident->complaint?->address }}</td>
  </tr>
  <tr>
    <td style="white-space: nowrap;">Contact Person</td>
    <td style="white-space: nowrap;">:</td>

    <td style="white-space: nowrap;">{{ $incident->complaint?->name }}</td>
  </tr>
  <tr>
    <td style="white-space: nowrap;">Hp Number</td>
    <td style="white-space: nowrap;">:</td>

    <td style="white-space: nowrap;">{{ $incident->complaint?->phone_no }}</td>
  </tr>
  <tr>
    <td style="white-space: nowrap;">Office Number</td>
    <td style="white-space: nowrap;">:</td>

    <td style="white-space: nowrap;">{{ $incident->complaint?->office_phone_no }}</td>
  </tr>
  <tr>
    <td style="white-space: nowrap;">Problem Desc.</td>
    <td style="white-space: nowrap;">:</td>

    <td>{{ $incident->information }}</td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<hr style="border: none; border-bottom: 1px dotted #000;"/>
<br>
<br>
<table>
  <tr>
    <td>Resolution</td>
    <td colspan="2">:</td>
  </tr>
  @foreach($incident->incidentResolution as $resolution)
  <tr>
    <td>{{ $resolution->created_at?->format('d/M/Y H:i:s') }}</td>
    <td>:</td>

    <td>{{ $resolution->notes }}</td>
  </tr>
  @endforeach
</table>

<br>
<br>
{{ $email_template->notes }}
</x-mail::message> --}}
<x-mail::message>
# Incident Report

<table style="width:100%;">
  <tr>
    <td style="white-space:nowrap;"><strong>Customer</strong></td>
    <td style="white-space:nowrap;">:</td>
    <td style="white-space:nowrap;">{{ $incident->complaint?->name }}</td>
  </tr>
  <tr>
    <td style="white-space:nowrap;"><strong>Location</strong></td>
    <td style="white-space:nowrap;">:</td>
    <td style="white-space:nowrap;">{{ $incident->complaint?->address }}</td>
  </tr>
  <tr>
    <td style="white-space:nowrap;"><strong>Contact Person</strong></td>
    <td style="white-space:nowrap;">:</td>
    <td style="white-space:nowrap;">{{ $incident->complaint?->name }}</td>
  </tr>
  <tr>
    <td style="white-space:nowrap;"><strong>Hp Number</strong></td>
    <td style="white-space:nowrap;">:</td>
    <td style="white-space:nowrap;">{{ $incident->complaint?->phone_no }}</td>
  </tr>
  <tr>
    <td style="white-space:nowrap;"><strong>Office Number</strong></td>
    <td style="white-space:nowrap;">:</td>
    <td style="white-space:nowrap;">{{ $incident->complaint?->office_phone_no }}</td>
  </tr>
  <tr>
    <td style="white-space:nowrap;vertical-align: top;"><strong>Problem Description</strong></td>
    <td style="white-space:nowrap;vertical-align: top;">:</td>
    <td>{{ $incident->information }}</td>
  </tr>
</table>
<br>
-------------------------------------------------------------------------------------------------------------------
<br>
<br>

# Resolution
<table style="width:100%;">
    @foreach($incident->incidentResolution as $resolution)
    <tr>
        <td style="width:24%"><strong>{{ $resolution->created_at?->format('d/M/Y H:i:s') }}</strong></td>
        <td style="width:1%">:</td>
        <td style="white-space:nowrap;width:75%;">{{ $resolution->notes }}</td>
    </tr>
    @endforeach
</table>

{{-- @foreach($incident->incidentResolution as $resolution)
**{{ $resolution->created_at?->format('d/M/Y H:i:s') }}**  | : | {{ $resolution->notes }}

@endforeach --}}
<br>
<br>
{{ $email_template->notes }}

</x-mail::message>