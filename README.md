������� ������ ����������

������ ������:
1) ������� ������ � GitHub
2) ������� �� ������ � �����
3) ���������� VirtualBox � Vagrant
4) ������� ������� � ������� � ������ �������
5) ��������� ������� vagrant up (������� ��������� � ������ y2aa-frontend.dev � y2aa-backend.dev)
6) ������������������ �� ����� � ����������� email (����������� ����� useFileTransport)
7) �� ������� ����� �� ssh �� ����������� ������ (vagrant ssh)
8) ������� � ����� /app
9) ��������� ./yii rbac/assign mail@xxx admin, ��� mail@xxx ��� email, ��� ������� �� ������������������.
10) ��������� �������� �� ������� ./yii test/generate 100 100
11) ������ ���������.

������ ������:
1) ���������� php 7.1, postgress � ���-������. 
2) �������� ����� ��� �������. �������� � ���������� ������ � �� ������
3) �������� �� � PostgreSQL
----------------------------------
sudo -u postgres psql -c "CREATE USER root WITH PASSWORD 'root'"
service postgresql restart
sudo -u postgres psql -c "CREATE DATABASE yii2advanced"
sudo -u postgres psql -c "CREATE DATABASE yii2advanced_test"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE yii2advanced TO root"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE yii2advanced_test TO root"
service postgresql restart
----------------------------------
4) ��������� ������� ����������, ������ ���� � ������� � ���������, � ����� ������. � ������� ���������� ������� ������ ��� ����������� � ��
5) ������� �� ������� � ����� � ��������
6) ��������� yii migrate
7) ������������������ � ������� � ����������� email ((����������� ����� useFileTransport))
8) ��������� ./yii rbac/assign mail@xxx admin, ��� mail@xxx ��� email, ��� ������� �� ������������������.
9) ��������� �������� �� ������� ./yii test/generate 100 100
10) ������ ���������.