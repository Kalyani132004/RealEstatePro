{{-- Usage: @include('emails.partials.button', ['url' => $url, 'label' => 'View Property']) --}}
<table role="presentation" cellpadding="0" cellspacing="0" style="margin: 24px 0;">
    <tr>
        <td style="border-radius: 999px; background-color: #1E3A5F;">
            <a href="{{ $url }}" class="rep-email-btn" style="display:inline-block; padding: 12px 28px; font-size:14px; font-weight:600; color:#ffffff; text-decoration:none; border-radius:999px;">
                {{ $label }}
            </a>
        </td>
    </tr>
</table>
