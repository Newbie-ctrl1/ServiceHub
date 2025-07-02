@extends('Chat.layouts.chat-layout')

{{-- This is the main chat page that uses the modular layout --}}
{{-- All functionality is now split into separate components for better maintainability --}}

{{-- Additional custom scripts can be added here if needed --}}
@push('scripts')
<script>
    // Any page-specific JavaScript can be added here
    console.log('Chat page loaded successfully');
</script>
@endpush