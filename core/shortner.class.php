<?php
final class UrlShortner
{
    protected $fp;

    public function
    __construct ($database = 'urls.db')
    {
        if (($lineas = file($database)) === FALSE)
			throw new Exception ("Error en base de datos");
		
        foreach ($lineas as $nLinea => $dato)
        {
			preg_match ('/^(.+?)\|(.+)\|(.+)$/', $dato, $matches);
			if ($matches[3] == "permanent" || $matches[3] > time())
			{
				$info[] = $dato ;
			}
        }
        $documento = implode($info, '');
        file_put_contents($database, $documento);

        if (($this->fp = fopen ($database, "a+")) === FALSE)
            throw new Exception ("Error en base de datos");
    }

    private function
    search ($key)
    {
        fseek ($this->fp, 0);
        while (!feof ($this->fp))
        {
            $line = fgets ($this->fp);
            if (preg_match ('/^' . $key . '\|/', $line))
                break;
        }
        if (!preg_match ('/^' . $key . '\|(.+)\|(.+)$/', $line, $matches))
            return false;

		return $matches[1];
    }

    private function
    searchLink ($key)
    {
        fseek ($this->fp, 0);
        while (!feof ($this->fp))
        {
            preg_match ('/^(.+?)\|(.+)\|(.+)$/', fgets ($this->fp), $matches);
			if (count($matches) > 0)
			{
				$line = array ($matches[1], $matches[2], $matches[3]);
				if ($line[1] == $key)
					break;
			}
        }
        if ($line[1] != $key)
            return false;
        return $line[0];
    }

    public function
    get ($key)
    {
        if (($link = $this->search ($key)) === false)
            throw new Exception ("Clave no encontrada");
        return $link;
    }

    private function
    generateCode ()
    {
        $voc = preg_split ('//', "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");
        do
        {
            list ($code, $random) = array ("", array_rand ($voc, 8));
            foreach ($random AS $ran)
            $code .= $voc[$ran];
        }
        while ($this->search ($code) !== false);
        return $code;
    }

    public static function
    checkLink ($url)
    {
        $url = rtrim (trim ($url), '/');
        if (!preg_match (
                    '/^[a-zA-Z]+[a-zA-Z0-9\+\-\.]*:\/\/((([a-zA-Z0-9;\/\?:@&=\+\$,]|%[0-9A-Fa-f]{2})+@)?((([a-zA-Z0-9]+|[a-zA-Z0-9]+[a-zA-Z0-9-]?[a-zA-Z0-9])\.            )*([a-zA-Z]+|[a-zA-Z]+[a-zA-Z0-9-]?[a-zA-Z0-9])[\.]?|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})(:[0-9]+)?|([a-zA-Z0-9\-_\.!~\*\'\(\)\$,;:@&=\+]|%[0-9A-Fa-f]{2})+)(\/([a-zA-Z0-9\-_\.!~\*\'\(\):@&=+&,]|%[0-9A-Fa-f]{2})*(;([a-zA-Z0-9\-_\.!~\*\'\(\):@&=+&,]|%[0-9A-Fa-f]{2})*)*(\/([a-zA-Z0-9\-_\.!~\*\'\(\):@&=+&,]|%[0-9A-Fa-f]{2})*(;([a-zA-Z0-9\-_\.!~\*\'\(\):@&=+&,]|%[0-9A-Fa-f]{2})*)*)*)?(\/([a-zA-Z0-9\-_\.!~\*\'\(\):@&=+&,]|%[0-9A-Fa-f]{2})*(;([a-zA-Z0-9\-_\.!~\*\'\(\):@&=+&,]|%[0-9A-Fa-f]{2})*)*(\/([a-zA-Z0-9\-_\.!~\*\'\(\):@&=+&,]|%[0-9A-Fa-f]{2})*(;([a-zA-Z0-9\-_\.!~\*\'\(\):@&=+&,]|%[0-9A-Fa-f]{2})*)*)*)?(\?([;\/\?:@&=\+\$,a-zA-Z0-9\-_\.!~\*\'\(\)]|%[0-9A-Fa-f]{2})*)?(#([;\/\?:@&=\+\$,a-zA-Z0-9\-_\.!~\*\'\(\)]|%[0-9A-Fa-f]{2})*)?$/',
                    $url))
            return "URL Invalida";

        return true;
    }

    public function
    insert ($url, $key, $temp)
    {
        if (($error = self::checkLink ($url)) !== true)
            throw new Exception ($error);

        if (($link = $this->searchLink ($url)) !== false)
            return $link;

        if ($key == "")
        {
            $key = $this->generateCode ();
        }
        else
        {
            if (($link = $this->search ($key)) !== false)
            {
                throw new Exception ("Clave en uso");
            }
        }
        fseek ($this->fp, 0, SEEK_END);
		
		if ($temp == "true")
			$delete = time() + strtotime('+1 day');
		else
			$delete = "permanent";
		
        fwrite ($this->fp ,"{$key}|{$url}|{$delete}\n");
        return $key;
    }

    public function
    __destruct ()
    {
        fclose ($this->fp);
    }
}
?>
