<?

/**
 * @class SessionHandlerNative
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class SessionHandlerNative implements ISessionHandler {
	
	public function open($sessionSavePath, $sessionName) {}
	public function close() {}
	public function read($sessionId) {}
	public function write($sessionId, $sessionData) {}
	public function destroy($sessionId) {}	
	public function gc($maxLifeTime) {}

}

?>
