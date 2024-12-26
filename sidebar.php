<div class="sidebar">
   <div class="logo_name">
   <h2><span>Parish</span>Track</h2>
   </div>
   <div class="links">
   <div class="dashed"></div>
    <a href="dashboard.php"> <i class="fas fa-home"></i> Dashboard</a>
   <div class="dashed"></div>
   <a href="add_revenue.php"> <i class="fas fa-dollar-sign"></i> Add Revenue</a>
   <div class="dashed"></div>
    <a href="view_revenue.php"> <i class="fas fa-chart-line"></i> View Revenue</a>
    <div class="dashed"></div>
   </div>
   <a href="">
   <div class="logout">
    <i class="fa-solid fa-power-off"></i> Logout
   </div>
   </a>
</div>

<style>
    .sidebar{
        height: 100vh;
        background-color: #fff;
        width: 250px;
        padding: 0 30px;
        position: absolute;
        left: 0;
    }
.links{
    display: flex;
    flex-direction: column;
    margin-top: 35px;
}

.links a{
    
    padding-block: 10px;
    color: #000;
}

.logout{
    position: absolute;
    bottom: 50px;
}
.dashed{
    border-top: 2px dashed #336AEA;
}

</style>