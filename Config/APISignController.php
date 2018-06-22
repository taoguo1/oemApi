<?php
return [ 
		'Agent' => [ 
				'resetPassword' => true,
				'logout' => true,
				'addAgent' => true,
				'editPush' => true,
				'setPayPassword' => true,
				'identity' => true,
				'agentTakeCash' => true 
		],
		'AgentBill' => [ ],
		'AgentCard' => [ 
				'postCard' => false,
				'putCard' => true,
				'deleteCard' => true 
		],
		'AgentFindPassword' => [ 
				'index' => true
		]
		,
		'AgentHierarchy' => [ 
				'add' => false,
				'setRate' => true,
				'tradeInviteCode' => true 
		],
		'AgentInviteCode' => [ ],
		'AgentLogin' => [ 
				'index' => false
		],
		'AgentMention' => [ ],
		'Balance' => [ ],
		'Bank' => [ ],
		'Banker' => [ ],
		'BaseAgent' => [ ],
		'BaseUser' => [ ],
		'Bill' => [ 
				'editBillDay' => true,
				'editRepaymentDay' => true 
		]
		,
		'CreditCard' => [ 
		    'postCard' => false,
				'deleteCard' => true,
				'putCard' => true 
		]
		,
		'DebitCard' => [ 
				'postCard' => true,
				'deleteCard' => true,
				'putCard' => true 
		]

		,
    /*
		'Gather' => [ 
				'index' => true 
		],
    */
		'GetAPISign' => [ ],
		'GetMsgCode' => [
				'getUserRegCode'=>true,
		 ],
		'News' => [ ],
		'Pay' => [ ],
    /*
		'Plan' => [ 
				'makePlan' => true,
				'finishPlan' => true,
				'delPlan' => true 
		]
		,
    */
		'RedPacket' => [ 
				'signin' => true 
		],
		'Sms' => [ 
				'index' => false
		]
		,
		'User' => [ 
				'logout' => false,
				'setPayPassword' => true,
				'editPush' => true,
				'identity' => false,
				'userBindInviteCode' => true,
				'Recharge' => true,
				'takeCash' => true,
                'checkSetPayPasswordInfo' => true
		]
		,
		'UserCollection' => [ ],
		'UserFindPassword' => [ 
				'index' => true
		],
		'UserLogin' => [ 
				'index' => true
		],
		'UserMention' => [ ],
//		'UserReg' => [
//				'index' => true
//		]
//
];