Name: test-spec
Summary: Test Spec
Version: 1
Release: 1.12
Group: Utilities/System
License: Copyright PCBlindado
Vendor: Cleber Rodrigues <http://www.cleberrodrigues.com.br>
Packager: Cleber Rodrigues <crodrigues@cleberrodrigues.com.br>
URL: http://cleberrodrigues.com.br
BuildRoot: %{_tmppath}/%{name}-root

Source: test-spec-%{version}.tbz2

BuildArch: noarch

AutoReqProv: no

Requires: httpd
Requires: php
Requires: php-mbstring
Requires: php-pdo
Requires: php-gd

%description
simple example of spec file

%prep
%setup -q 

%install

%clean

%post

%files

%changelog
* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.12
tst
- Revision: b189fc7

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.11
tst
- Revision: b189fc7

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.10
teste

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.9
 - tst
- Revision: b189fc7

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.8
 - tst
- Revision: b189fc7

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.7
 - tst
- Revision: b189fc7

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.6
 - tst
- Revision: b189fc7

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.5
 - tst
- Revision: b189fc7

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.4
 - tst
- Revision: b189fc7

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.3
- tst (#4567), (#12121212)
- Initial commit (#1345)
- Revision: 6b05dae, b189fc7

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.2
- tst (#4567), (#12121212)
- Initial commit (#1345)
- Revision: 6b05dae, b189fc7

* Fri Aug 23 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> 1-1.1
- tst (#4567), (#12121212)
- Initial commit (#1345)
- Revision: 6b05dae, b189fc7

* Fri Aug 08 2013 Cleber Rodrigues <cleber@cleberrodrigues.com.br> - 1-0.1
- primeira buid
