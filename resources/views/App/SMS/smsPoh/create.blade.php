@extends('App.main.navBar')
@section('sms_active','active')
@section('sms_active_show', 'active show')
@section('sms_poh_active_show', 'active show')
@section('sms_poh_active', 'active ')

<x-sms.create service="smspoh"></x-sms.create>
