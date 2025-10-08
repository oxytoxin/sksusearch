  <div class="mt-4">
      @if ($is164)
          @include('fund-views.164T')
      @else
          @if ($fundClusterWfpId === 2)
              @include('fund-views.163')
          @else
              @if (!$showPre)
                  @include('fund-views.101')
              @else
                  @include('fund-views.164T')
              @endif
          @endif
      @endif
  </div>
