@props([
    'value'
])

@if($value->parent)
<div class="mt-3 ps-3">
    @if($value->label)
    <h3 class="h3">
        {{ $value->label }} :
    </h3>
    @endif

    <div class="mt-3 row">

        <div class="widget-code-container mb-3" style="position: relative; background: #f9f9f9; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem; font-family: monospace; font-size: 0.9rem; color: #2d3748; overflow: hidden;">
            <!-- Sticky button -->
            <button onclick="copyWidgetSnippet()" style="position: absolute; top: 0.5rem; right: 0.5rem; background: #017E84; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 5px; cursor: pointer; z-index: 10;">
                Copy
            </button>

            <!-- Scrollable content -->
            <div style="overflow-x: auto; white-space: nowrap; padding-top: 1rem;" id="scrollableSnippet">
                <div id="widgetSnippet">
                    <div><span style="color: #718096;">&lt;!-- Start of Ndako Script --&gt;</span></div>
                    <div>&lt;script src="{{ env("APP_URL") }}/api/v1/embed/booking.js"&gt;&lt;/script&gt;</div>
                    <div>&lt;script&gt;</div>
                    <div style="padding-left: 1rem;">document.addEventListener('DOMContentLoaded', function () {</div>
                    <div style="padding-left: 2rem;">NdakoEmbed.init(,</div>
                    <div style="padding-left: 3rem;">'bookingFormContainer',</div>
                    <div style="padding-left: 3rem;">'{{ env("APP_URL") }}/api/v1',</div>
                    <div style="padding-left: 3rem;">'YOUR PUBLIC KEY',</div>
                    <div style="padding-left: 3rem;">'YOUR SECRET KEY',</div>
                    <div style="padding-left: 3rem;">'YOUR CALLBACK PAGE'</div>
                    <div style="padding-left: 2rem;">);</div>
                    <div style="padding-left: 1rem;">});</div>
                    <div>&lt;/script&gt;</div>
                    <div><span style="color: #718096;">&lt;!-- End of Ndako Script --&gt;</span></div>
                </div>
            </div>
        </div>

        <!-- Instructions for Non-Tech Users -->
        <div class="instructions" style="color: #555;">
            <h3 class="h3">What You Need to Do:</h3>
            <ol class="mb-2">
              <li class="mb-1">Copy the full snippet above.</li>
              <li class="mb-1">Paste it just before the <code>&lt;/body&gt;</code> tag of your website, or wherever you want the booking form to appear.</li>
              <li class="mb-1">Replace the following:
                <ul class="mt-1">
                    <li class="mb-1"><strong>bookingFormContainer</strong> → with the ID of the element (e.g., a <code>&lt;div&gt;</code>) where the form should appear.<br>
                        For example: <code>&lt;div id="myBookingForm"&gt;&lt;/div&gt;</code>.</li>
                    <li class="mb-1"><b>YOUR PUBLIC KEY</b> → with your public API key (provided above).</li>
                    <li class="mb-1"><b>YOUR SECRET KEY</b> → with your secret API key (keep this private).</li>
                    <li class="mb-1"><b>YOUR CALLBACK PAGE</b> → with your custom "Thank You" page link, e.g. <b>https://example.com/thank-you</b>.</li>
                </ul>
              </li>
            </ol>
            For advanced configuration or customizing the display, please check our <a href="https://docs.ndako.tech/user-docs/booking-embed" target="_blank" style="color:#017E84;">Booking Embed Documentation</a>.
          </div>
        </div>


    </div>

</div>
@endif
