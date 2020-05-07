## Cidades Brasileiras

Script em PHP e base de dados em SQL para criação de uma imagem PNG com o mapa do Brasil.

Todas as 5570 cidades brasileiras, a partir das respectivas coordenadas geográficas, serão projetadas nos pixels de uma imagem plana. Cada ponto na projeção terá uma dimensão e cor de acordo com a população da cidade. Código desenvolvido apenas para estudo.

 :eyes: A base de cidades, fornecida aqui, está cuidadosamente corrigida. Os nomes dos municípios foram conferidos com a base do IBGE, também contém o código do município (estado+município) e o gentílico (y otras cositas más). A população é a estimativa de 2019.


### Implementações em código:

- Dimensão da imagem obtida opcionalmente por variável externa pelo método GET.
- Margem automática para comportar a área do mapa dentro da imagem.
- Dimensão dos pontos distribuídos em escala logarítmica pela população.
- Cores dos pontos em RGB gerado por função senoidal.
- Correção das coordenadas geográficas para desconsiderar os hemisférios.
- Tipo do documento de resposta no padrão text/html ou em image/png.

### Exemplo da imagem gerada:

![Mapa do Brasil](img/brasil.png?raw=true)

### Referências:

- Bumgardner, J. Making annoying rainbows in javascript. Krazydad. October 13, 2006. Disponível em: <https://krazydad.com/tutorials/makecolors.php>

- IBGE Conheça Cidades e Estados do Brasil. Disponível em: <https://cidades.ibge.gov.br/>

- IBGE Estimativas da população residente no Brasil e unidades da federação com data de referência em 1º de Julho de 2019. Disponível em: <ftp://ftp.ibge.gov.br/Estimativas_de_Populacao/Estimativas_2019/>

- Webster, B. GPS Coordinates to Pixels. Stack Overflow. May 30, 2011. Disponível em: <https://stackoverflow.com/questions/6172355/how-to-convert-lat-long-to-an-xy-coordinate-system-e-g-utm-and-then-map-this/6172384#6172384>
