@extends('App.main.navBar')

@section('sms_active','active')
@section('sms_active_show', 'active show')
@section('sms_twilio_active_show', 'active show')
@section('sms_twilio_active', 'active ')

<x-sms.create service="twilio"></x-sms.create>
