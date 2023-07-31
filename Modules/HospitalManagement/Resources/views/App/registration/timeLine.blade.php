



@extends('App.main.navBar')
@section('styles')
{{-- css file for this page --}}
<style>
    .group-label {
        font-size: 1rem !important;
        /* padding: 10px; */
        font-weight: bold !important;
    }
    .vis-label {
  border-bottom: 1px solid #000000  !important;
  margin-top: 5px;
  margin-bottom: 5px;
}

</style>
@endsection
@section('registration_icon','active')
@section('hospital_registration_show','active show')
@section('registration_timeline_active_show','active')

@section('title')
<!--begin::Heading-->
<h1 class="text-dark fw-bold my-0 fs-4">Registration</h1>
<!--end::Heading-->
<!--begin::Breadcrumb-->
<ul class="breadcrumb fw-semibold fs-base my-1 fs-5">
    <li class="breadcrumb-item text-muted">Hospital</li>
    <li class="breadcrumb-item text-dark">Registration</li>
</ul>
<!--end::Breadcrumb-->
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl" id="kt_content_container">
        <div class="card mb-5 mb-xl-10">
            <div class="card-body">
                <div class="row">
                    <div class="col-2 mb-5">
                        <label for="" class="form-label">Room Type</label>
                        <select name="" id="room_type" class="form-select form-select-sm" data-placeholder="Select Room Rate" data-control="select2">
                            <option value="All"  selected>All Type</option>
                            @foreach ($room_rates as $rate)
                            <option value="{{$rate->id}}">{{$rate->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="kt_docs_vistimeline_group"></div>
                <div class="my-5">
                    (Scroll with the mouse and see the items being focus automatically on the timeline)
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Content-->
@endsection

@push('scripts')
<script>
    var now = Date.now();
    var rooms = @json($rooms);

    var options = {
        stack: true,
        maxHeight: 640,
        horizontalScroll: false,
        verticalScroll: true,
        zoomKey: "ctrlKey",
        start: Date.now() - 1000 * 60 * 60 * 24 * 1, // minus 3 days
        end: Date.now() + 1000 * 60 * 60 * 24 * 10, // plus 1 month approx.
        orientation: {
            axis: "top",
            item: "center",
        },  groupTemplate: function(group) {
    // Add a separator line after each group
    return '<div class="group">' +
             '<div class="group-content">' +
               group.content +
             '</div>' +
             '<div class="group-separator"></div>' +
           '</div>';
  },
        type: 'font-size: 10px;',
    };
    var groups = new vis.DataSet();
    var items = new vis.DataSet();

    var count = 10;

    for (var i = 0; i < count; i++) {
        var start = now + 1000 * 60 * 60 * 24 * (i + Math.floor(Math.random() * 7));
        var end = start + 1000 * 60 * 60 * 24 * (1 + Math.floor(Math.random() * 5));

        // Create the main group
        var groupId = i;
        var groupName = "Room " + i;
        var groupOrder = i;

        groups.add({
            id: groupId,
            content: groupName,
            order: groupOrder,
        });

        // Create the subgroup
        var subGroupId = i + count; // Use a unique ID for the subgroup
        var subGroupName = "Subgroup " + i;
        var subgroupOrder = i; // Define the order of the subgroup

        groups.add({
            id: subGroupId,
            content: subGroupName,
            subgroupOrder: subgroupOrder,
            subgroupStack: false // Do not stack subgroups
        });

        // Add the item to the subgroup
        items.add({
            id: i,
            group: groupId,
            subgroup: subGroupId,
            start: start,
            end: end,
            type: "range",
            content: "Item " + i,
        });
            items.add({
            id: i+10,
            group: groupId,
            subgroup: subGroupId,
            start: start,
            end: end,
            type: "range",
            content: "Item " + i,
        });
    }

    // create a Timeline
    var container = document.getElementById("kt_docs_vistimeline_group");
    var timeline = new vis.Timeline(container);
    timeline.setOptions(options);
    timeline.setGroups(groups);
    timeline.setItems(items);


</script>
@endpush























