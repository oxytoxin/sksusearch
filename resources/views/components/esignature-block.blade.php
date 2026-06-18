@if($signature)
    <x-signature-block
        :signature="$signature"
        :width="$width"
        :max-height="$maxHeight"
        :top="$top"
        :bottom="$bottom"
        :left="$left"
        :translate-x="$translateX"
        :translate-y="$translateY"/>

    @if($signedBy || $signedAt)
        <x-esign-info
            :name="$signedBy"
            :datetime="$signedAt"
            :textclass="$infoClass"
            :offset-x="$infoOffsetX"
            :offset-y="$infoOffsetY"
            :show-description="$showInfo"/>
    @endif
@endif
