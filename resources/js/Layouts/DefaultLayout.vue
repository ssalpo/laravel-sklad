<template>
    <div class="wrapper">
        <Nav/>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <slot/>
            </div>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

    </div>

    <toasts />
</template>

<script>
import Nav from "../Shared/Nav.vue";
import Toasts from "../Shared/Toasts.vue";

export default {
    components: {Toasts, Nav}
}
</script>
