<?php
/**
 *
 * @package       phpBB Extension - Linked Accounts
 * @copyright (c) 2018 Flerex
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace rusefi\web\service;

class utils
{
   	/** @var \phpbb\user */
   	protected $user;

   	/** @var \phpbb\auth\auth */
   	protected $auth;

   	/** @var \phpbb\config\config $config */
   	protected $config;

   	/** @var \phpbb\db\driver\factory */
   	protected $db;

   	/** @var string */
   	protected $tokens_table;

	public function __construct(\phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\factory $db, $tokens_table)
	{
		$this->user = $user;
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->tokens_table = $tokens_table;
	}

	 function gen_uuid() {
            return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

                // 16 bits for "time_mid"
                mt_rand( 0, 0xffff ),

                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand( 0, 0x0fff ) | 0x4000,

                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand( 0, 0x3fff ) | 0x8000,

                // 48 bits for "node"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
            );
        }


	public function get_token($key)
	{
	    $sql = 'SELECT user_id, token
           	FROM ' . $this->tokens_table . ' ' . 'WHERE user_id = ' . (int) $key;

   		$result = $this->db->sql_query($sql);
   		$user = $this->db->sql_fetchrow();
   		$this->db->sql_freeresult($result);

   		if (is_null($user['token'])) {

            $new_token = 'todoo';//gen_uuid();

			$sql_ary = array(
			    'user_id'		=> $key,
				'created_at'	=> time(),
				'token'	        => $new_token,
			);

			$sql = 'INSERT INTO ' . $this->tokens_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
			$this->db->sql_query($sql);

   		    return 'Brand new ' .  $new_token;

   		}

   		return 'Existing ' . $user['token'];



	}

	public function reset_token($key)
	{


	}


}