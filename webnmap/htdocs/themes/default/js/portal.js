
/** portlet_CreateListItem
 * Temporary function to create items in the
 * 'add item' list when a portlet is created.
 * Should be replaced with the disabled construct
 * event when s:move works with textnodes.
 */
function portlet_CreateListItem(sPortletId, sCaption) {

	// Create reference to listitem
	var sXML_setReference = '<s:with b:target="id('+sPortletId+')"><s:setatt b:listitem-id="'+sPortletId+'-listitem"/></s:with>';
	bpc.execute(sXML_setReference);

	// Create listitem with reference to portlet
	var sListItem = '<b:add-portlet-listitem id="'+sPortletId+'-listitem" b:portlet-id="'+sPortletId+'">'+sCaption+'</b:add-portlet-listitem>';

	// Copy to "add item" list
	var sXML_createListItem = '<s:render b:destination="id(\'add-portlet-list\')" b:mode="aslastchild">'+sListItem+'</s:render>';
	//alert(sXML_createListItem);
	bpc.execute(sXML_createListItem);
}


/** portlet_chat_CreateMessage
 * Temporary function to create messages in the
 * chat portlet when the user enters a new message.
 * Should be replaced when s:move works with textnodes.
 */
function portlet_chat_CreateMessage(sMessage) {

	// Create message
	var sChatMessage = '<div class="portlet-chat-message">&lt;<strong>John</strong>&gt; '+sMessage+'</div>';

	// Copy to message log
	var sXML_createChatMessage = '<s:render b:destination="id(\'portlet-chat-messagebox\')" b:mode="aslastchild">'+sChatMessage+'</s:render>';
	bpc.execute(sXML_createChatMessage);
}