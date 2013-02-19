<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<link rel="stylesheet" href="css/jquery.mobile-1.1.1.css">
<script src="js/jquery.min.js"></script>
<script src="js/jquery.mobile-1.1.1.js"></script>
<script src="js/cookie.js"></script>
<link rel="stylesheet" href="css/datepicker.css" /> 
<script src="js/jQuery.ui.datepicker.js"></script>
<script>
  function gotoHome(){
    window.location='index.php';
  }
   function changePass() {
                                        var oldpass=$("#oldpass").val();
                                        var password=$("#password").val();
                                        var confirmpass=$("#confirmpass").val();
                                        if((oldpass!="")&&(password!="")&&(confirmpass!=""))
                                        {
                                        var uid=getCookie("uid");var token=getCookie("token");
                                        $.ajax({
                                               type: "POST",
                                               url: "api/account.php",
                                               dataType: "json",
                                               data: { uid: uid, token: token,oldpass:oldpass,password:password,confirmpass:confirmpass,task:'changePass'}
                                               }).success(function( msg ) {
                                                          alert("Successful"); window.location="account.php";
                                                          }).fail(function(msg){alert("Fail To Update Password");window.location="account.php";});
                                        }else alert("Please Enter the password correctly");
                                        }
  
                                        function changeProfile()
                                        {
                                        var fname=$("#fname").val();
                                        var lname=$("#lname").val();
                                        var title=$("#title").val();
										var birth=$("#birth").val();
										var phone=$("#phone").val();
										
										var sex=$("#sex").val();
                                        var email=getCookie('email');
                                        var uid=getCookie("uid");var token=getCookie("token");
                                        $.ajax({
                                               type: "POST",
                                               url: "api/account.php",
                                               dataType: "json",
                                               data: { uid: uid, token: token,email:email,fname:fname,lname:lname,title:title,birth:birth,phone:phone,sex:sex,task:'changeProfile'}
                                               }).success(function( msg ) {
                                                          alert("Successful"); window.location="account.php";
                                                          }).fail(function(msg){alert("Fail To Update");window.location="account.php";});
                                        
                                        }
										
										function changePreference()
										{
											
									     var eMotel=$("#checkbox1").val();
                                        var eBinn=$("#checkbox2").val();
                                        var eBhotel=$("#checkbox3").val();
                                        var eSchain=$("#checkbox4").val();
                                        var mBinn=$("#checkbox5").val();
                                        var mIbhotel=$("#checkbox6").val();
                                        var mSchain=$("#checkbox7").val();
										var mBchain=$("#checkbox8").val();
										var uIbhotel=$("#checkbox9").val();
								        var uSchain=$("#checkbox10").val();
								        var uBchain=$("#checkbox11").val();
								        var uChotel=$("#checkbox12").val();	
										
										var select1=$("#select1").val();
                                       
                                        var select2=$("#select2").val();
										  var select3=$("#select3").val();
								          var select4=$("#select4").val();
										  
										  var select5=$("#select5").val();
										  
								var select6=$("#select6").val();
								var select7=$("#select7").val();
								var select8=$("#select8").val();
								var select9=$("#select9").val();
								var select10=$("#select10").val();
								var select11=$("#select11").val();
										
										var uid=getCookie("uid");
                                        var token=getCookie("token");
									
										
											
										$.ajax({
											   type: "POST",
                                               url: "api/account.php",
                                               dataType: "json",
                                               data:{ uid:uid,token:token,eMotel:eMotel,eBinn:eBinn,eBhotel:eBhotel,eSchain:eSchain,mBinn:mBinn,mIbhotel:mIbhotel,mSchain:mSchain,mBchain:mBchain,uIbhotel:uIbhotel,uSchain:uSchain,uBchain:uBchain,uChotel:uChotel,select1:select1,select2:select2,select3:select3,select4:select4,select5:select5,select6:select6,select7:select7,select8:select8,select9:select9,select10:select10,select11:select11,task:'changePreference'}
											   											   
											   }
											   ).success(function( msg ) {
                                                          alert("Successful"); window.location="account.php";
                                                          }).fail(function(msg){alert("Fail To Update");window.location="account.php";});
											
											
											
											
																																	
																																												
											
											}
                                        function changePassport()
                                        {
                                        var passport=$("#passportNo").val();
                                        var expireDate=$("#expireDate").val();
                                        var issueDate=$("#issueDate").val();
                                         var issueCountry=$("#issueCountry").val();
                                        var issueCity=$("#issueCity").val();
                                        var email=getCookie("email");
                                        var uid=getCookie("uid");
                                        var token=getCookie("token");
										var sex=$("#sex2").val();
										var address=$("#address").val();
										var birthPlace=$("#birthPlace").val();
										var nationality=$("#nationality").val();
										var birth=$("#birth2").val();
										
										
									
                                        $.ajax({
                                               type: "POST",
                                               url: "api/account.php",
                                               dataType: "json",

                                               data: { uid: uid, token: token,email:email,issueCountry:issueCountry,issueCity:issueCity,passport:passport,expireDate:expireDate,issueDate:issueDate,sex:sex,address:address,birthPlace:birthPlace,nationality:nationality,birth:birth,task:'changePassport'}
                                               }).success(function( msg ) {
                                                          alert("Successful"); window.location="account.php";
                                                          }).fail(function(msg){alert("Fail To Update");window.location="account.php";});
                                        
                                        }
       function getPreference() {
        var uid=getCookie("uid");
        var token=getCookie("token");
        $.ajax({
               type: "POST",
               url: "api/account.php",
               dataType: "json",
               data: { uid:uid, token: token,task:'getPreference' }
               }).success(function( msg ) {
				 
                          var hotels=msg;
                          var hotel=$("#userPreference");
                          hotel.html('');
                            if(hotels)
                          {
                          for(var i=0;i<hotels.length;i++)
                          { 
						  
							  for(var j=1;j<23;j++){
                            var newli=$('<li>' +hotels[i][j]+'</li>').appendTo(hotel);
							  }
                          }
                        }
                          hotel.listview( "refresh" );
                          }).fail(function(msg){console.log(msg);});
    }
























                                        function getProfile()
                                        {
                                        
                                        var uid=getCookie("uid");
										var token=getCookie("token");
                                        var email=getCookie("email");
									
                                        $.ajax({
                                               type: "GET",
                                               url: "api/account.php",
                                               dataType: "json",
                                               data: { uid: uid, token: token,email:email}
                                               }).success(function( msg ) {
												
												   
												
												          $("#username").append(msg.fname);
                                                          $("#fname").val(msg.fname);
                                                          $("#lname").val(msg.lname);
                                                          $("#title").val(msg.title);
														  $("#address").val(msg.uaddress);
												$("#birthPlace").val(msg.birthPlace);
														  $("#birth").val(msg.birth);
														  $("#birth2").val(msg.birth);
														 $("#sex").val(msg.sex);
														  $("#sex2").val(msg.sex);
														
                                                          $("#passportNo").val(msg.passport);
														   $("#expireDate").val(msg.expireDate);
                                                          $("#issueDate").val(msg.issueDate);
                                                          $("#issueCountry").val(msg.issueCountry);
														  $("#issueCity").val(msg.issueCity);
														  $("#phone").val(msg.phone);
														  
							   
                               $("#nationality").val(msg.nationality);
                                                         
                                                          }).fail(function(msg){alert("Unauthorized");//window.location="index.php";
                                                        });
                                        }$(document).ready(function() {
                                          $("#expireDate").datepicker();
                                          $("#issueDate").datepicker();
if(checkCookie("uid")==0){
    window.location="index.php";
}else{
  var uid=getCookie("uid");
  getProfile();
}
});
</script>
</head>
<body>
<div data-role="page">

  <div data-role="header" data-theme="b">
    <h1>My Account ¨C User Profile for </h1>
    <a href="mine.php" data-icon="home" data-iconpos="notext" data-rel="back">Home</a>
  </div><!-- /header -->

  <div data-role="content">
  <div data-role="collapsible-set">
    <div id="changeprofile" data-role="collapsible">
    <h3><img src="css/images/login.png"/>Basic Information</h3>
    <p>     
          <label>First Name:</label><input type="text" id="fname" name="fname"/>
          <label>Last Name:</label><input type="text" id="lname" name="lname"/>
          <label>Salutation:</label><input type="text" id="title" name="title"/>
          <label>Mobile Number:</label><input type="text" id="phone" name="phone"/>
          <label>Email:</label><input type="text" id="email" name="email"/>
          <label>Birthday:</label><input type="text" id="birth" name="birth"/>
          <label>Sex:</label><input type="text" id="sex" name="sex"/>
                <button data-theme="b" onClick="changeProfile()" onKeyPress="changeProfile()">Save Changes</button>
    </p>
  </div>
  </div>
    <div data-role="collapsible-set">
        <div id="changeprofile" data-role="collapsible">
        <h3><img src="css/images/login.png"/>Passport Information</h3>
        <p>
                Passport Number:
                <input type="text" id="passportNo" name="passportNo" placeholder="Passport No"/>
                <table>
                  <tr>
                    <td>
                       Date of Issue:
                      <input type="text" id="issueDate" name="issueDate" placeholder="Passport Issue Date"/>
                    </td>
                    <td>
                       Date of Expiry:
                      <input type="text" id="expireDate" name="expireDate" placeholder="Passport Expire Date"/>
                    </td>
                     <td>
                       Country of Issue:	
                      <input type="text" id="issueCountry" name="issueCountry" placeholder="Issue Country"/>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      City of Issue:
                      <input type="text" id="issueCity" name="issueCity" placeholder="Issue City"/>
                    </td>
                    <td>
                     Place of Birth:
                     <input type="text" id="birthPlace" name="birthplace" palceholder="Place of Birth"/>
                     </td>
                     <td>
                     Date of Birth:
                     <input type="text" id="birth2" name="birth2" placeholder="Date of Birth"/>
                     </td>
                  </tr>
                  
                  <tr>
                  <td>
                  Sex:
                  <input type="text" id="sex2"  name="sex2" placeholder="Sex"/>
                  </td>
                  <td>
                  Nationality:
                  <input type="text" id="nationality" name="nationality" placeholder="Nationality"/>
                  </td>
                  <td>
                  Address:
                  <input type="text" id="address" name="address" placeholder="Address"/>
                  </td>
                  </tr>
                  
                </table>
                


                <br><br><br>
                <br><br><br>
                <br><br>
                <button data-theme="b" onClick="changePassport()" onKeyPress="changePassport()">Save Changes</button>
        </p>
    </div>
    </div>
<div data-role="collapsible-set">
<div data-role="collapsible">
<h3><img src="css/images/login.png"/>Digital Passport</h3>
<p>
<iframe src="uploadPassport.php" seamless="seamless"></iframe>
<img width="200px" id="myPassport">
</p>
</div>
</div>
<div data-role="collapsible-set">
<div data-role="collapsible">
<h3><img src="css/images/login.png"/>Your ID Photo</h3>
<p>
<iframe src="uploadPhoto.php" seamless="seamless"></iframe>
<img width="50px" id="myPhoto">
</p>
</div>
</div>
  <div data-role="collapsible-set">
    <div id="changepass" data-role="collapsible">
    <h3><img src="css/images/login.png"/>Change Password</h3>
    <p>
      <form method="post">
      <label class="ui-hidden-accessible">Old Password:</label>
          <input type="text" id="oldpass" name="oldpass" placeholder="Old Password"/>
      <label class="ui-hidden-accessible">New Password:</label>
          <input type="password" id="password" name="password" placeholder="New Password"/>
      <label class="ui-hidden-accessible">Confirm Password:</label>
          <input type="password" id="confirmpass" name="confirmpass" placeholder="Confirm Password"/>
      <button data-theme="b" onClick="changePass()" onKeyPress="changePass()">Change Password</button>
      </form>
    </p>
  </div>
  </div>
<div data-role="collapsible-set">
      <div data-role="collapsible">
        <h3><img src="css/images/login.png"/> Your Preference </h3>
        <div data-role="collapsible-set">
          <div data-role="collapsible">
            <h3> Hotel Type </h3>
            <div id="checkboxes1" data-role="fieldcontain">
              <fieldset data-role="controlgroup" data-type="vertical">
                <legend> Economy Hotel </legend>
                <input id="checkbox1" name="Motel" type="checkbox" value="Motel" >
                <label for="checkbox1"> Motel </label>
                <input id="checkbox2" name="Bed &amp; Breakfast inn" type="checkbox" value="Bed &amp; Breakfast inn">
                <label for="checkbox2"> Bed &amp; Breakfast inn </label>
                <input id="checkbox3" name="Boutique Hotel" type="checkbox" value="Boutique Hotel">
                <label for="checkbox3"> Boutique Hotel </label>
                <input id="checkbox4" name="Standardized hotel affiliated/operated by recognized chain"
                                type="checkbox" value="Standardized hotel affiliated/operated by recongnized chain">
                <label for="checkbox4"> Standardized hotel affiliated / operated by recognized chain </label>
              </fieldset>
            </div>
            <div id="checkboxes2" data-role="fieldcontain">
              <fieldset data-role="controlgroup" data-type="vertical">
                <legend> Midrange Hotel </legend>
                <input id="checkbox5" name="Bed &amp; Breakfast inn" type="checkbox" value="Bed &amp; Breakfast inn">
                <label for="checkbox5"> Bed &amp; Breakfast inn </label>
                <input id="checkbox6" name="Independent Boutique Hotel" type="checkbox" value="Independent Boutique Hotel">
                <label for="checkbox6"> Independent Boutique Hotel </label>
                <input id="checkbox7" name="Standardized hotel affiliated/operated by recognized chain"
                                type="checkbox" value="Standardized hotel affiliated/operated by recognized chain">
                <label for="checkbox7"> Standardized hotel affiliated / operated by recognized chain </label>
                <input id="checkbox8" name="Boutique hotel operated by recognized chain"
                                type="checkbox" value="Boutique hotel operated by recognized chain">
                <label for="checkbox8"> Boutique hotel operated by recognized chain </label>
              </fieldset>
            </div>
            <div id="checkboxes3" data-role="fieldcontain">
              <fieldset data-role="controlgroup" data-type="vertical">
                <legend> Upscale Hotel </legend>
                <input id="checkbox9" name="Independent Boutique Hotel" type="checkbox" value="Independent Boutique Hotel">
                <label for="checkbox9"> Independent Boutique Hotel </label>
                <input id="checkbox10" name="Standardized hotel affiliated/operated by recognized chain"
                                type="checkbox" value="Standardized hotel affiliated/operated by recognized chain">
                <label for="checkbox10"> Standardized hotel affiliated / operated by recognized chain </label>
                <input id="checkbox11" name="Boutique hotel operated by recognized chain"
                                type="checkbox" value="Boutique hotel operated by recognized chain">
                <label for="checkbox11"> Boutique hotel operated by recognized chain </label>
                <input id="checkbox12" name="Convention Stype Hotel" type="checkbox" value="Convention Stype Hotel">
                <label for="checkbox12"> Convention Stype Hotel </label>
              </fieldset>
            </div>
          </div>
        </div>
        <div data-role="collapsible-set">
          <div data-role="collapsible">
            <h3> Technology </h3>
            <div data-role="fieldcontain">
              <label for="selectmenu1"> Internet Access in Room </label>
              <select id="select1" name="Internet Access in Room">
                <option value="Not Available" > Not Available </option>
                <option value="Available for Hourly Usage" > Available for Hourly Usage </option>
                <option value="Available for 24 hour Usage"> Available for 24 hour Usage </option>
                <option value="Available for Free"> Available for Free </option>
              </select>
            </div>
            <div data-role="fieldcontain">
              <label for="selectmenu3"> Business Center </label>
              <select id="select2" name=" Business Center">
                <option value="Not Available"> Not Available </option>
                <option value="A centrally Located Business Center"> A centrally Located Business Center </option>
                <option value="Multiple business kiosks throughout  the facilities"> Multiple business kiosks throughout the facilities </option>
                <option value="Mini-business center(printer,fax,etc)available in room"> Mini-business center(printer,fax,etc)available in room </option>
              </select>
            </div>
            <div data-role="fieldcontain">
              <label for="selectmenu4"> Internet Reservation </label>
              <select id="select3" name=" Internet Reservation">
                <option value="No"> No </option>
                <option value="Yes"> Yes </option>
              </select>
            </div>
            <div data-role="fieldcontain">
              <label for="selectmenu5"> Internet Check-in </label>
              <select id="select4" name=" Internet Check-in">
                <option value="Yes"> No </option>
                <option value="Yes"> Yes </option>
              </select>
            </div>
            <div data-role="fieldcontain">
              <label for="selectmenu6"> Mobile Check-In </label>
              <select id="select5" name="  Mobile Check-In">
                <option value="No"> No </option>
                <option value="Yes"> Yes </option>
              </select>
            </div>
          </div>
        </div>
        <div data-role="collapsible-set">
          <div data-role="collapsible">
            <h3> Customization </h3>
            <div data-role="fieldcontain">
              <label for="selectmenu7"> Pet Policy </label>
              <select id="select6" name="Pet Policy">
                <option value="No Pets"> No Pets </option>
                <option value="Small Pets"> Small Pets </option>
              </select>
            </div>
            <div data-role="fieldcontain">
              <label for="selectmenu8"> Flexible Check-In </label>
              <select id="select7" name="Flexible Check-In">
                <option value="No"> No </option>
                <option value="Yes"> Yes </option>
              </select>
            </div>
            <div data-role="fieldcontain">
              <label for="selectmenu8"> Room Customization </label>
              <select id="select8" name=" Room Customization">
                <option value="No"> No </option>
                <option value="Yes"> Yes </option>
              </select>
            </div>
            <div data-role="fieldcontain">
              <label for="selectmenu9"> Childcare </label>
              <select id="select9" name="Childcare">
                <option value="Not Available"> Not Available </option>
                <option value="In room nanny facility at extra charge"> In room nanny facility at extra charge </option>
                <option value="In room nanny facility  + kids club(6-12yrs) at extra charge"> In room nanny facility + kids club(6-12yrs) at extra charge </option>
                <option value="In room nanny facility  + kids club(6-12yrs) +day care(6 mo older) at extra charge"> In room nanny facility + kids club(6-12yrs) +day care(6 mo older) at extra
                charge </option>
              </select>
            </div>
            <div data-role="fieldcontain">
              <label for="selectmenu10"> Kitchen </label>
              <select id="select10" name="  Kitchen">
                <option value="Available"> Available </option>
                <option value="Coffee-maker available at no extra cost"> Coffee-maker available at no extra cost </option>
                <option value="Coffee-maker available at no extra cost+small microwave+fridge"> Coffee-maker available at no extra cost+small microwave+fridge </option>
              </select>
            </div>
            <div data-role="fieldcontain">
              <label for="selectmenu11"> Laundry </label>
              <select id="select11" name=" Laundry">
                <option value="In Room Washer at no extra cost"> In Room Washer at no extra cost </option>
                <option value="Iron with Iron Table"> Iron with Iron Table </option>
              </select>
            </div>
          </div>
           <button data-theme="b" onClick="changePreference()" onKeyPress="changePreference()">Save Changes</button>
     <button data-theme="b" onClick="getPreference()" onKeyPress="getPreference()">Get Preference</button>
           <ul id="userPreference" data-role="listview" data-filter="true" data-filter-placeholder="Search hotels..." data-filter-theme="d"data-theme="d" data-divider-theme="d"></ul>
        </div>
       
      </div>
    </div>
</div>
  <div data-role="footer" data-theme="b"><h4>Copyright&copy;Asplan2012</h4></div> 
</div><!-- /page -->

</body>
</html>
