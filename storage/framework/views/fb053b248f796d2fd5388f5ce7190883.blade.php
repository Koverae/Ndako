    <?php $layout->viewContext->mergeIntoNewEnvironment($__env); ?>

    @extends($layout->view, $layout->params)

    @section($layout->slotOrSection)
        {!! $content !!}
    @endsection