<div class="example">
<legend>LOGIN</legend>
    <form method="post">
		<fieldset>
                
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" style="color:#F00"><?php echo $error; ?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td width="38%">                                
                    <label>Username</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="uname" type="text" id="uname" placeholder="Type Username" >
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                    <label>Password</label>
                    <div class="input-control password" data-role="input-control">
                        <input name="passwd" type="password" id="passwd" placeholder="Type Password" autofocus>
                        <button class="btn-reveal" tabindex="-1"></button>
                    </div>
    
                    <div class="input-control checkbox" data-role="input-control">
                        <label class="inline-block">
                            <input type="checkbox" />
                            <span class="check"></span>
                            Remember Me
                        </label>
                    </div>
                    <br>
                    <input name="submit" type="submit" id="submit" value="Submit">
                    <input type="reset" value="Cancel">
                    
                    <div style="margin-top: 20px"></div>
            
                           </td>
                <td width="62%">&nbsp;</td>
              </tr>
            </table>
 		</fieldset>
            </form></div>

            
        