<x-mail::message>
# Laporan Insiden

<table style="width:100%;">
  
  <tr>
    <td style="white-space:nowrap;"><strong>Nama Cawangan</strong></td>
    <td style="white-space:nowrap;">:</td>
    <td style="white-space:nowrap;">{{ $incident->complaintUser?->branch?->name }}</td>
  </tr>
  <tr>
    <td style="white-space:nowrap;"><strong>Pengadu</strong></td>
    <td style="white-space:nowrap;">:</td>
    <td style="white-space:nowrap;">{{ $incident->complaintUser?->name }}</td>
  </tr>
  <tr>
    <td style="white-space:nowrap;"><strong>Emel Pengadu</strong></td>
    <td style="white-space:nowrap;">:</td>
    <td style="white-space:nowrap;">{{ $incident->complaintUser?->email }}</td>
  </tr>
  <tr>
    <td style="white-space:nowrap;"><strong>No Telefon Bimbit</strong></td>
    <td style="white-space:nowrap;">:</td>
    <td style="white-space:nowrap;">{{ $incident->complaintUser?->phone_no }}</td>
  </tr>
  <tr>
    <td style="white-space:nowrap;"><strong>No Telefon Pejabat</strong></td>
    <td style="white-space:nowrap;">:</td>
    <td style="white-space:nowrap;">{{ $incident->complaintUser?->office_phone_no }}</td>
  </tr>
  <tr>
    <td style="white-space:nowrap;vertical-align: top;"><strong>Keterangan</strong></td>
    <td style="white-space:nowrap;vertical-align: top;">:</td>
    <td>{{ $incident->information }}</td>
  </tr>
</table>
<br>
-------------------------------------------------------------------------------------------------------------------
<br>
<br>

# Resolusi
<table style="width:100%;">
    @foreach($incident->incidentResolution as $resolution)
    <tr>
        <td style="white-space:nowrap;width:29%"><strong>{{ $resolution->created_at?->locale('ms')->format('d/m/Y H:i:s') }}</strong></td>
        <td style="width:1%">:</td>
        <td style="white-space:nowrap;width:70%;">
          @if($resolution->action_codes == App\Models\ActionCode::INITIAL)
          Initial Response
          @else
        {{ $resolution->solution_notes }}
          @endif
        </td>
    </tr>
    @endforeach
</table>

{{-- @foreach($incident->incidentResolution as $resolution)
**{{ $resolution->created_at?->format('d/M/Y H:i:s') }}**  | : | {{ $resolution->notes }}

@endforeach --}}
<br>
<br>
{{ $email_template?->notes }}

</x-mail::message>