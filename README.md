# Minecraft Sunucu Listesi Web Sitesi

Bu proje, **PHP** ve **PDO** kullanılarak oluşturulmuş dinamik ve duyarlı bir Minecraft sunucu listeleme web sitesidir. Kullanıcıların Minecraft sunucularını görüntülemesine, oylamasına ve yönetmesine olanak tanıyan, şık ve kullanıcı dostu bir arayüz sunar.

## Özellikler

### Kullanıcı Arayüzü
- **Ana Sayfa**: En popüler Minecraft sunucularını, durumları, oy sayıları ve bağlantı bilgileriyle birlikte görüntüler.
- **Sunucu Detay Sayfası**: Belirli bir sunucuya ait açıklama, oy sayısı ve istatistikler gibi detaylı bilgileri gösterir.
- **Sunucu Ekleme Sayfası**: Kullanıcıların IP, isim, açıklama ve logo gibi gerekli detaylarla kendi Minecraft sunucularını listeye eklemesine olanak tanır.

### İşlevsellikler
- **Sunucu Oylama**: Kullanıcılar favori sunucularına oy verebilir.
- **Sunucu İstatistikleri**: Toplam oy, çevrimiçi durumu ve ping gibi sunucu istatistiklerini gösterir.
- **Güvenli Girdi İşleme**: PDO kullanılarak tüm kullanıcı girdileri doğrulanır ve SQL enjeksiyonuna karşı korunur.
- **Duyarlı Tasarım**: Masaüstü ve mobil cihazlar için tamamen optimize edilmiştir.


## Kurulum

1. Projeyi cihazınıza indirin:
   ```bash
   git clone https://github.com/MODLOFF/minecraft-server-list.git
   ```

2. Config dosyasını düzenleyin.
   ```bash
   config/database.php
   ```
3. URL kısımlarını düzenleyin.
   ```bash
   Her sayfadaki url kısımlarını kendi site urlnize göre düzenleyin
   Varsayılan hali: /mcserverlist/
   ```
