<div class="">
    <div>
        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
            @if (hasView('campaign report'))
            <li class="nav-item">
                <a class="nav-link  text-active-gray-700 fw-semibold active" data-bs-toggle="tab" href="#campaign_report">Campaign
                    Report</a>
            </li>
            @endif
            @if (hasView('attendance'))
                <li class="nav-item">
                    <a class="nav-link  text-active-gray-700 fw-semibold {{!hasView('campaign report')?'active' :'' }}" data-bs-toggle="tab" href="#attendanceTab">Attendance</a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link text-active-gray-700 fw-semibold {{!hasView('campaign report') && !hasView('attendance')? 'active' :'' }}" data-bs-toggle="tab" href="#kt_tab_pane_5">Photo Gallery</a>
            </li>

            @if (hasView('campaign report'))
            <li class="nav-item">
                <a class="nav-link text-active-gray-700 fw-semibold {{!hasView('campaign report') && !hasView('attendance')? 'active' :'' }}"
                    data-bs-toggle="tab" href="#productSummaryReport">Product Summary Report</a>
            </li>
            @endif
        </ul>
        <div class="tab-content " id="myTabContent"  >
            @if (hasView('campaign report'))
                <div class="tab-pane fade show active " id="campaign_report" role="tabpanel">
                <livewire:fieldservice.over-all-report-table   :deraultCampaignId="$campaign_id"  />
                </div>
            @endif
            @if (hasView('attendance'))
            <div class="tab-pane fade {{!hasView('campaign report')? 'show active' :'' }}" id="attendanceTab"  role="tabpanel">
                <livewire:fieldservice.AttendanceList :campaign_id="$campaign_id" />
            </div>
            @endif

            <div class="tab-pane fade  scrollableDiv {{!hasView('campaign report') && !hasView('attendance')? 'show active' :'' }}" id="kt_tab_pane_5" role="tabpanel"  >
                <livewire:fieldservice.ImageGralleryComponent :campaign_id="$campaign_id" />
            </div>

            <div class="tab-pane fade" id="productSummaryReport" role="tabpanel">
                <livewire:fieldservice.CampaignProductSummeryReport :campaign_id="$campaign_id" />
            </div>
        </div>





    </div>

</div>
