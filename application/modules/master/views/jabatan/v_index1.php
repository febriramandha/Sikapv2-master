<style type="text/css">
	
	section {
  min-height: 100%;
  display: flex;
  justify-content: center;
  flex-direction: column;
  padding: 50px 0;
  position: relative; }
  section .github-badge {
    position: absolute;
    top: 0;
    left: 0; }
  section h1 {
    text-align: center;
    margin-bottom: 70px; }
  section .hv-container {
    flex-grow: 1;
    overflow: auto;
    justify-content: center; }

.basic-style {
  background-color: #EFE6E2; }
  .basic-style > h1 {
    color: #ac2222; }

p.simple-card {
  margin: 0;
  background-color: #fff;
  color: #DE5454;
  padding: 30px;
  border-radius: 7px;
  min-width: 100px;
  text-align: center;
  box-shadow: 0 3px 6px rgba(204, 131, 103, 0.22); }

.hv-item-parent p {
  font-weight: bold;
  color: #DE5454; }

.management-hierarchy {
  background-color: #303840; }
  .management-hierarchy > h1 {
    color: #FFF; }
  .management-hierarchy .person {
    text-align: center; }
    .management-hierarchy .person > img {
      height: 110px;
      border: 5px solid #FFF;
      border-radius: 50%;
      overflow: hidden;
      background-color: #fff; }
    .management-hierarchy .person > p.name {
      background-color: #fff;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 12px;
      font-weight: normal;
      color: #3BAA9D;
      margin: 0;
      position: relative; }
      .management-hierarchy .person > p.name b {
        color: rgba(59, 170, 157, 0.5); }
      .management-hierarchy .person > p.name:before {
        content: '';
        position: absolute;
        width: 2px;
        height: 8px;
        background-color: #fff;
        left: 50%;
        top: 0;
        transform: translateY(-100%); }
</style>
<!-- Default unordered list markup -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Data Jenjang Jabatan</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>
	
	<div class="card-body">
		<!--Management Hierarchy-->
    <section class="management-hierarchy">
        <div class="hv-container">
            <div class="hv-wrapper">

                <!-- Key component -->
                <div class="hv-item">

                    <div class="hv-item-parent">
                        <div class="person">
                            <img src="<?php echo base_url('public/images/profile_user.png') ?>" alt="">
                            <p class="name">
                               	Martias / Sekretaris Daerah
                            </p>
                        </div>
                    </div>

                    <div class="hv-item-children">

                        <div class="hv-item-child">
                            <!-- Key component -->
                            <div class="hv-item">

                                <div class="hv-item-parent">
                                    <div class="person">
                                        <img src="<?php echo base_url('public/images/profile_user.png') ?>" alt="">
                                        <p class="name">
                                            Annie Wilner <b>/ Asisten 1</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="hv-item-children">

                                    <div class="hv-item-child">
                                        <div class="person">
                                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="">
                                            <p class="name">
                                                Lihat Struktur</b>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                         <div class="hv-item-child">
                            <!-- Key component -->
                            <div class="hv-item">

                                <div class="hv-item-parent">
                                    <div class="person">
                                        <img src="<?php echo base_url('public/images/profile_user.png') ?>" alt="">
                                        <p class="name">
                                            Annie Wilner <b>/ Asisten 2</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="hv-item-children">

                                    <div class="hv-item-child">
                                        <div class="person">
                                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="">
                                            <p class="name">
                                                Lihat Struktur</b>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="hv-item-child">
                            <!-- Key component -->
                            <div class="hv-item">

                                <div class="hv-item-parent">
                                    <div class="person">
                                        <img src="<?php echo base_url('public/images/profile_user.png') ?>" alt="">
                                        <p class="name">
                                            Annie Wilner <b>/ Asisten 3</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="hv-item-children">

                                    <div class="hv-item-child">
                                        <div class="person">
                                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="">
                                            <p class="name">
                                                Lihat Struktur</b>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                       

                	</div>

            </div>
        </div>
    </section>
	</div>
</div>
<!-- /default unordered list markup -->

<script type="text/javascript">
	$('.open_modal').click(function() {
       alert('a');
	})
	 $('.tree-default').fancytree({
            init: function(event, data) {
                $('.has-tooltip .fancytree-title').tooltip();
            }
        });
</script>