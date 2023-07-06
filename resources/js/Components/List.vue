<script setup>
</script>
<template>
    <div class="py-3">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <slot name="title"></slot>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <slot name="thead"></slot>
                    </thead>
                    <tbody>
                        <slot name="tbody"></slot>
                    </tbody>
                </table>
            </div>
        </div>
        <slot name="buttons"></slot>
        <slot name="other"></slot>
    </div>
</template>
