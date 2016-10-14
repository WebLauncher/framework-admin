<div class="admin_page clearfix" style="width:100%;">
	<div class="content" id="content">
		<center>
			<div id="admin_login">
				
				<form action="{$current}" method="post" id="login_form_admin">
				<fieldset class="formview">
					<center><img src="{$skin_images}logo.png" width="100%" align="center"/></center>
				</fieldset>
					{if isset($p.actions[0]) && $p.actions[0]=="forgot"}
					<input type="hidden" name="a" value="login_reset"/>
					<input type="hidden" name="_type" value="admin" />
					<input type="hidden" name="goto" value="{$current_full}" />
					<fieldset class="formview">
						<div class="title">
							{tr}Reseteaza parola{/tr}
						</div>
						<div class="notice">
							{tr}Completati utilizatorul pentru a primi un e-mail cu o noua parola de access pe adresa de mail.{/tr}
						</div>
						<label for="_username">{tr}Utilizator{/tr}</label>
						<center>
							<input type="text" placeholder="{tr}User{/tr}" class="text" name="_username" value="{$p.state._username|default:""}" id="_username" style="width:90%"/>
						</center>
						{validator form="login_form_admin" field="_username" rule="required" message="Completati utilizatorul!"}
						<script>
							jQuery("#_username").focus();

						</script>
						<div class="clearfix">
							<div class="floatleft">
								<button type="submit" class="positive">
									<i class="icon icon-envelope"></i><span>{tr}Trimite{/tr}</span>
								</button>
							</div>
							<div class="floatright">
								<button type="button" class="negative" onclick="go_to('{$current}')">
									<i class="icon icon-reply"></i>{tr}Cancel{/tr}
								</button>
							</div>
						</div>
					</fieldset>
					{else}
					<input type="hidden" name="a" value="login"/>
					<input type="hidden" name="_type" value="admin" />
					<fieldset class="formview">
						<div class="title">
							{tr}Autentificare{/tr}
						</div>
						{if isset($p.session.messages) && count($p.session.messages)}
						<div id="message_zone" class="clearfix" style="float:none;">
							{messages class_error='error' class_success='success'}
							<div class="clearfix **class_type**">
								**message**
							</div>
							{/messages}
						</div>
						{/if}
						<div class="clearfix">
							<div class="floatleft">								
								<input type="text" placeholder="{tr}User{/tr}" class="text" name="_username" value="{$p.state._username|default:""}" tabindex="1" id="_username" style="width:140px;"/>
								{validator form="login_form_admin" field="_username" rule="required" message="Completati utilizatorul!"}
								<script>
									jQuery("#_username").focus();

								</script><br/>
								<input type="checkbox" tabindex="3" name="_remmember" id="_remmember" value="checked" />
								<label for="_remmember">{tr}tine-ma logat{/tr}</label>
								<br/>
							</div>
							<div class="floatleft">
								
									<input type="password" placeholder="{tr}Password{/tr}" class="text" name="_password" id="_password" tabindex="2"  style="width:140px;"/>
								
								{validator form="login_form_admin" field="_password" rule="required" message="Completati parola!"}
								<br/><a style="margin-left:0.3em" href="{$current}?a=forgot" tabindex="4">{tr}Am uitat parola.{/tr}</a>
							</div>
						</div>
						{if isset($p.errors[0]) && $p.errors[0]=="login"}
						<div class="error">
							{tr}Logare nereusita!{/tr}
						</div>
						{/if}						
						<div class="clearfix">
							<div class="floatleft">
								<button type="submit" tabindex="5" class="positive">
									<i class="icon icon-check"></i><span>{tr}Intra{/tr}</span>
								</button>
							</div>							
						</div>
					</fieldset>
					{/if}
				</form>
			</div>
		</center>
	</div>
</div>
