<input
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
                'type' => 'hidden',
                $applyStateBindingModifiers('wire:model') => $getStatePath(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-fo-hidden'])); ?>

/>
<?php /**PATH D:\APP\TUMAINI LETU\TUMAINI LETU SYSTEM\TUMAINI_LETU_SYSTEM\vendor\filament\forms\resources\views/components/hidden.blade.php ENDPATH**/ ?>