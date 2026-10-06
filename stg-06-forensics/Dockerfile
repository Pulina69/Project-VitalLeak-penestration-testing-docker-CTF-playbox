FROM ubuntu:22.04

# Install OpenSSH server
RUN apt-get update && apt-get install -y openssh-server

# Create the required privilege separation directory
RUN mkdir -p /run/sshd

# Generate the missing SSH host keys (fixes the instant crash)
RUN ssh-keygen -A

# Explicitly allow password authentication
RUN echo "PasswordAuthentication yes" >> /etc/ssh/sshd_config

# Create the user from Stage 5 and set the password
RUN useradd -m -s /bin/bash aurastaff
RUN echo 'aurastaff:OpenSesame123' | chpasswd

# PLANT THE SUID BACKDOOR
RUN cp /bin/bash /usr/local/bin/sys_backup
RUN chmod +s /usr/local/bin/sys_backup

# Plant the final Stage 6 flag in the restricted root directory
RUN echo "Vital{R00t_Pr1v_Esc_SU1D}" > /root/flag.txt
RUN chmod 600 /root/flag.txt

# Start the SSH service in the foreground with error logging enabled (-e)
EXPOSE 22
CMD ["/usr/sbin/sshd", "-D", "-e"]